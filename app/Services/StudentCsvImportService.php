<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Course;
use App\Enums\Gender;
use App\Enums\NstpComponent;
use App\Models\AuditLog;
use App\Models\CsvUpload;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use Exception;
use Generator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @phpstan-type ImportStats array{imported: int, updated: int, skipped: int}
 * @phpstan-type ParsedStudentRow array{
 *     csv_upload_id: int,
 *     school_year_id: int|null,
 *     nstp_component: string,
 *     serial_number: string,
 *     last_name: string,
 *     first_name: string,
 *     middle_name: string|null,
 *     course: string,
 *     gender: string,
 *     birth_date: string|null,
 *     city_address: string|null,
 *     province_address: string|null,
 *     contact_number: string|null,
 *     email: string|null,
 *     created_at: string,
 *     updated_at: string
 * }
 */
class StudentCsvImportService
{
    private const int CHUNK_SIZE = 1000;

    /**
     * @return array{imported: int, updated: int, skipped: int, errors: list<string>}
     */
    public function import(UploadedFile $file, User $user, NstpComponent $component, string $duplicateAction = 'skip'): array
    {
        $fileHash = $this->guardAgainstDuplicateUpload($file);

        $csvIterator = $this->readCsv($file->getRealPath());
        $headerMap = $this->initializeCsvAndGetHeaderMap($csvIterator);

        /** @var ImportStats $stats */
        $stats = ['imported' => 0, 'updated' => 0, 'skipped' => 0];

        /** @var list<string> $errors */
        $errors = [];

        /** @var array<string, bool> $seenEmails */
        $seenEmails = [];

        /** @var array<string, bool> $seenSerials */
        $seenSerials = [];

        $filePath = $file->store('csv_uploads');

        DB::transaction(function () use ($csvIterator, $headerMap, $component, $duplicateAction, $file, $filePath, $fileHash, $user, &$stats, &$errors, &$seenEmails, &$seenSerials) {
            $csvUpload = CsvUpload::create([
                'user_id' => $user->id,
                'school_year_id' => null,
                'nstp_component' => $component->value,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_hash' => $fileHash,
            ]);

            /** @var list<ParsedStudentRow> $chunk */
            $chunk = [];
            $rowNumber = 1;
            $firstSchoolYearId = null;

            while ($csvIterator->valid()) {
                $row = $csvIterator->current();
                $rowNumber++;
                $csvIterator->next();

                if (empty(array_filter($row))) {
                    continue;
                }

                $parsedRow = $this->parseRow($row, $headerMap, $component, $csvUpload->id, $rowNumber, $errors);

                if (! $parsedRow) {
                    continue;
                }

                $firstSchoolYearId ??= $parsedRow['school_year_id'];
                $chunk[] = $parsedRow;

                if (\count($chunk) >= self::CHUNK_SIZE) {
                    $this->processChunk($chunk, $duplicateAction, $stats, $seenEmails, $seenSerials);
                    $chunk = [];
                }
            }

            if (! empty($chunk)) {
                $this->processChunk($chunk, $duplicateAction, $stats, $seenEmails, $seenSerials);
            }

            $csvUpload->update([
                'school_year_id' => $firstSchoolYearId,
                'imported_count' => $stats['imported'],
                'updated_count' => $stats['updated'],
            ]);

            AuditLog::create([
                'auditable_type' => CsvUpload::class,
                'auditable_id' => $csvUpload->id,
                'event' => 'created',
                'new_values' => [
                    'file_name' => $file->getClientOriginalName(),
                    'component' => $component->value,
                    'imported' => $stats['imported'],
                    'updated' => $stats['updated'],
                    'skipped' => $stats['skipped'],
                ],
                'message' => "Bulk CSV Import: Imported {$stats['imported']} records, updated {$stats['updated']} records from '{$file->getClientOriginalName()}'",
                'user_id' => $user->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
            ]);
        });

        return [
            'imported' => $stats['imported'],
            'updated' => $stats['updated'],
            'skipped' => $stats['skipped'],
            'errors' => $errors,
        ];
    }

    public function rollback(CsvUpload $csvUpload): void
    {
        DB::transaction(function () use ($csvUpload) {
            $deletedCount = Student::where('csv_upload_id', $csvUpload->id)->delete();

            if (Storage::exists($csvUpload->file_path)) {
                Storage::delete($csvUpload->file_path);
            }

            AuditLog::create([
                'auditable_type' => CsvUpload::class,
                'auditable_id' => $csvUpload->id,
                'event' => 'deleted',
                'old_values' => [
                    'file_name' => $csvUpload->file_name,
                    'records_removed' => $deletedCount,
                ],
                'message' => "CSV Import Rollback: Permanently removed {$deletedCount} records imported from '{$csvUpload->file_name}'",
                'user_id' => auth()->id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
            ]);

            $csvUpload->delete();
        });
    }

    private function guardAgainstDuplicateUpload(UploadedFile $file): string
    {
        $fileHash = hash_file('sha256', $file->getRealPath());

        if (CsvUpload::where('file_hash', $fileHash)->exists()) {
            throw new Exception('This CSV file content was already uploaded previously.');
        }

        return $fileHash;
    }

    /**
     * @param Generator<int, array<int, string|null>> $csvIterator
     *
     * @return array<string, int>
     */
    private function initializeCsvAndGetHeaderMap(Generator $csvIterator): array
    {
        $header = $csvIterator->current();

        if (! $header) {
            throw new Exception('CSV file is empty or formatted incorrectly.');
        }

        /** @var array<string, int> $headerMap */
        $headerMap = array_flip(array_map(
            fn ($h) => Str::of((string) $h)->replaceMatches('/[\x00-\x1F\x7F-\xFF]/', '')->trim()->toString(),
            $header
        ));

        $this->validateHeaders($headerMap);

        $csvIterator->next();

        return $headerMap;
    }

    /**
     * @param list<ParsedStudentRow> $chunk
     * @param ImportStats $stats
     * @param array<string, bool> $seenEmails
     * @param array<string, bool> $seenSerials
     */
    private function processChunk(array $chunk, string $duplicateAction, array &$stats, array &$seenEmails, array &$seenSerials): void
    {
        /** @var list<string> $serials */
        $serials = array_values(array_column($chunk, 'serial_number'));

        /** @var list<string> $emails */
        $emails = array_values(array_filter(array_column($chunk, 'email')));

        $existingSerials = $this->getExistingSerials($serials);
        $existingEmails = $this->getExistingEmails($emails);

        $chunk = $this->sanitizeDuplicateEmails($chunk, $existingSerials, $existingEmails, $seenEmails);

        [$toInsert, $toUpdate] = $this->categorizeRecords($chunk, $existingSerials, $duplicateAction, $stats, $seenSerials);

        $this->executeBulkOperations($toInsert, $toUpdate, $stats);
    }

    /**
     * @param list<string> $emails
     *
     * @return array<string, string>
     */
    private function getExistingEmails(array $emails): array
    {
        if (empty($emails)) {
            return [];
        }

        /** @var array<string, string> */
        return Student::whereIn(DB::raw('UPPER(email)'), array_map('strtoupper', $emails))
            ->pluck('serial_number', 'email')
            ->mapWithKeys(fn ($serial, $email) => [strtolower($email) => $serial])
            ->toArray();
    }

    /**
     * @param list<string> $serials
     *
     * @return array<string, bool>
     */
    private function getExistingSerials(array $serials): array
    {
        if (empty($serials)) {
            return [];
        }

        /** @var array<string, bool> */
        return Student::whereIn(DB::raw('UPPER(serial_number)'), array_map('strtoupper', $serials))
            ->pluck('serial_number')
            ->mapWithKeys(fn ($s) => [strtoupper($s) => true])
            ->toArray();
    }

    /**
     * @param list<ParsedStudentRow> $chunk
     * @param array<string, bool> $existingSerials
     * @param array<string, string> $existingEmails
     * @param array<string, bool> $seenEmails
     *
     * @return list<ParsedStudentRow>
     */
    private function sanitizeDuplicateEmails(array $chunk, array $existingSerials, array $existingEmails, array &$seenEmails): array
    {
        foreach ($chunk as $i => $row) {
            $email = $row['email'];

            if ($email === null) {
                continue;
            }

            $emailLower = strtolower($email);

            if (isset($seenEmails[$emailLower])) {
                $chunk[$i]['email'] = null;
                continue;
            }

            if (isset($existingEmails[$emailLower])) {
                $ownerSerial = $existingEmails[$emailLower];

                if (strtoupper($ownerSerial) !== strtoupper($row['serial_number'])) {
                    $chunk[$i]['email'] = null;
                    continue;
                }
            }

            $seenEmails[$emailLower] = true;
        }

        return $chunk;
    }

    /**
     * @param list<ParsedStudentRow> $chunk
     * @param array<string, bool> $existingSerials
     * @param ImportStats $stats
     * @param array<string, bool> $seenSerials
     *
     * @return array{0: list<ParsedStudentRow>, 1: list<ParsedStudentRow>}
     */
    private function categorizeRecords(array $chunk, array $existingSerials, string $duplicateAction, array &$stats, array &$seenSerials): array
    {
        $toInsert = [];
        $toUpdate = [];

        foreach ($chunk as $row) {
            $serialKey = strtoupper($row['serial_number']);

            if (isset($seenSerials[$serialKey]) || isset($existingSerials[$serialKey])) {
                if ($duplicateAction === 'update') {
                    $toUpdate[] = $row;
                } else {
                    $stats['skipped']++;
                }
            } else {
                $toInsert[] = $row;
                $seenSerials[$serialKey] = true;
            }
        }

        return [$toInsert, $toUpdate];
    }

    /**
     * @param list<ParsedStudentRow> $toInsert
     * @param list<ParsedStudentRow> $toUpdate
     * @param ImportStats $stats
     */
    private function executeBulkOperations(array $toInsert, array $toUpdate, array &$stats): void
    {
        if (! empty($toInsert)) {
            Student::insert($toInsert);
            $stats['imported'] += \count($toInsert);
        }

        if (! empty($toUpdate)) {
            $updateColumns = [
                'csv_upload_id',
                'school_year_id',
                'first_name',
                'middle_name',
                'last_name',
                'course',
                'gender',
                'birth_date',
                'city_address',
                'province_address',
                'contact_number',
                'email',
                'updated_at',
            ];

            Student::upsert($toUpdate, ['serial_number'], $updateColumns);
            $stats['updated'] += \count($toUpdate);
        }
    }

    /**
     * @param array<int, string|null> $row
     * @param array<string, int> $headerMap
     * @param list<string> $errors
     *
     * @return ParsedStudentRow|null
     */
    private function parseRow(array $row, array $headerMap, NstpComponent $component, int $csvUploadId, int $rowNumber, array &$errors): ?array
    {
        $serialNumber = $this->getValue($row, $headerMap, 'serialNo');
        $lastName = $this->getValue($row, $headerMap, 'lastName');
        $firstName = $this->getValue($row, $headerMap, 'firstName');

        if (! $serialNumber || ! $lastName || ! $firstName) {
            $errors[] = "Row {$rowNumber}: Missing Serial No, Last Name, or First Name.";

            return null;
        }

        $schoolYearStr = $this->getValue($row, $headerMap, 'schoolYear');
        $now = now()->toDateTimeString();

        return [
            'csv_upload_id' => $csvUploadId,
            'school_year_id' => $schoolYearStr ? $this->resolveSchoolYearId($schoolYearStr) : null,
            'nstp_component' => $component->value,
            'serial_number' => $serialNumber,
            'last_name' => $lastName,
            'first_name' => $firstName,
            'middle_name' => $this->cleanValue($this->getValue($row, $headerMap, 'middleName')),
            'course' => $this->parseCourse($this->getValue($row, $headerMap, 'course')),
            'gender' => $this->parseGender($this->getValue($row, $headerMap, 'gender'))->value,
            'birth_date' => $this->parseBirthDate($this->getValue($row, $headerMap, 'birthdate')),
            'city_address' => $this->cleanValue($this->getValue($row, $headerMap, 'cityAddress')),
            'province_address' => $this->cleanValue($this->getValue($row, $headerMap, 'provinceAddress')),
            'contact_number' => $this->cleanContactNumber($this->getValue($row, $headerMap, 'contact')),
            'email' => $this->cleanEmail($this->getValue($row, $headerMap, 'email')),
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    /**
     * @return Generator<int, array<int, string|null>>
     *
     * @throws Exception
     */
    private function readCsv(string $path): Generator
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new Exception('Unable to open the uploaded CSV file.');
        }

        while (($data = fgetcsv($handle)) !== false) {
            /** @var array<int, string|null> $data */
            yield $data;
        }

        fclose($handle);
    }

    /**
     * @param array<string, int> $headerMap
     *
     * @throws Exception
     */
    private function validateHeaders(array $headerMap): void
    {
        foreach (['serialNo', 'lastName', 'firstName', 'course', 'gender', 'schoolYear'] as $req) {
            if (! isset($headerMap[$req])) {
                throw new Exception("Missing required CSV column header: '{$req}'.");
            }
        }
    }

    /**
     * @param array<int, string|null> $row
     * @param array<string, int> $headerMap
     */
    private function getValue(array $row, array $headerMap, string $column): ?string
    {
        if (! isset($headerMap[$column], $row[$headerMap[$column]])) {
            return null;
        }

        return Str::trim((string) $row[$headerMap[$column]]);
    }

    private function cleanContactNumber(?string $value): ?string
    {
        $cleaned = $this->cleanValue($value);

        if ($cleaned === null) {
            return null;
        }

        $digits = Str::of($cleaned)->replaceMatches('/\D/', '');

        if ($digits->isEmpty()) {
            return null;
        }

        if ($digits->startsWith('639') && $digits->length() >= 12) {
            return (string) $digits->substr(2, 10)->prepend('0');
        }

        if ($digits->startsWith('09')) {
            return (string) $digits->substr(0, 11);
        }

        if ($digits->startsWith('9')) {
            return (string) $digits->prepend('0')->substr(0, 11);
        }

        return $cleaned;
    }

    private function cleanValue(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $string = Str::of($value)->trim();

        if ($string->isEmpty() || \in_array($string->upper()->toString(), ['NI', 'NONE', 'N/A', 'NULL', '-'], true)) {
            return null;
        }

        return $string->toString();
    }

    private function cleanEmail(?string $value): ?string
    {
        $cleaned = $this->cleanValue($value);

        return $cleaned && filter_var($cleaned, FILTER_VALIDATE_EMAIL) ? $cleaned : null;
    }

    private function parseGender(?string $value): Gender
    {
        $upper = Str::of((string) $value)->trim()->upper()->toString();

        if (\in_array($upper, ['M', 'MALE', '1'], true)) {
            return Gender::MALE;
        }
        if (\in_array($upper, ['F', 'FEMALE', '2'], true)) {
            return Gender::FEMALE;
        }

        return Gender::OTHER;
    }

    private function parseCourse(?string $value): string
    {
        $cleaned = Str::of((string) $value)->trim()->upper()->toString();
        $legacyAliases = ['BSHRT' => Course::BSHM->value, 'ABECON' => Course::BSECON->value];

        if (isset($legacyAliases[$cleaned])) {
            return $legacyAliases[$cleaned];
        }

        foreach (Course::cases() as $case) {
            if (Str::lower($case->value) === Str::lower($cleaned)) {
                return $case->value;
            }
        }

        return $cleaned ?: Course::BSIT->value;
    }

    private function parseBirthDate(?string $value): ?string
    {
        $cleaned = $this->cleanValue($value);
        if (! $cleaned) {
            return null;
        }

        try {
            if (Str::of($cleaned)->test('/^\d{1,2}\/\d{1,2}\/\d{4}$/')) {
                return Carbon::createFromFormat('d/m/Y', $cleaned)->format('Y-m-d');
            }

            return Carbon::parse($cleaned)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    private function resolveSchoolYearId(string $schoolYearString): int
    {
        [$start, $end] = Str::of($schoolYearString)->trim()->explode('-');

        return SchoolYear::firstOrCreate(['start_year' => (int) $start, 'end_year' => (int) $end])->id;
    }
}