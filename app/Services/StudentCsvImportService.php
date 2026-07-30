<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Course;
use App\Enums\Gender;
use App\Enums\NstpComponent;
use App\Models\CsvUpload;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use Exception;
use Generator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * @phpstan-type ImportStats array{imported: int, updated: int, skipped: int}
 * @phpstan-type ParsedStudentRow array{
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
    private const CHUNK_SIZE = 1000;

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
        $firstSchoolYearId = null;

        $this->processRowsInChunks($csvIterator, $headerMap, $component, $duplicateAction, $stats, $errors, $firstSchoolYearId);

        $this->logSuccessfulUpload($file, $fileHash, $user, $firstSchoolYearId, $stats);

        return [
            'imported' => $stats['imported'],
            'updated' => $stats['updated'],
            'skipped' => $stats['skipped'],
            'errors' => $errors,
        ];
    }

    private function guardAgainstDuplicateUpload(UploadedFile $file): string
    {
        $fileHash = hash_file('sha256', $file->getRealPath());

        if (CsvUpload::where('file_hash', $fileHash)->exists()) {
            throw new Exception('This CSV file was already uploaded previously.');
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
        $headerMap = array_flip(array_map(fn ($h) => trim(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', (string)$h)), $header));

        $this->validateHeaders($headerMap);

        $csvIterator->next();

        return $headerMap;
    }

    /**
     * @param Generator<int, array<int, string|null>> $csvIterator
     * @param array<string, int> $headerMap
     * @param ImportStats $stats
     * @param list<string> $errors
     */
    private function processRowsInChunks(
        Generator $csvIterator,
        array $headerMap,
        NstpComponent $component,
        string $duplicateAction,
        array &$stats,
        array &$errors,
        ?int &$firstSchoolYearId
    ): void {
        DB::transaction(function () use ($csvIterator, $headerMap, $component, $duplicateAction, &$stats, &$errors, &$firstSchoolYearId) {
            /** @var list<ParsedStudentRow> $chunk */
            $chunk = [];
            $rowNumber = 1;

            while ($csvIterator->valid()) {
                $row = $csvIterator->current();
                $rowNumber++;
                $csvIterator->next();

                if (empty(array_filter($row))) {
                    continue;
                }

                $parsedRow = $this->parseRow($row, $headerMap, $component, $rowNumber, $errors);

                if (! $parsedRow) {
                    continue;
                }

                $firstSchoolYearId ??= $parsedRow['school_year_id'];
                $chunk[] = $parsedRow;

                if (\count($chunk) >= self::CHUNK_SIZE) {
                    $this->processChunk($chunk, $duplicateAction, $stats);
                    $chunk = [];
                }
            }

            if (! empty($chunk)) {
                $this->processChunk($chunk, $duplicateAction, $stats);
            }
        });
    }

    /**
     * @param ImportStats $stats
     */
    private function logSuccessfulUpload(UploadedFile $file, string $fileHash, User $user, ?int $schoolYearId, array $stats): void
    {
        if ($stats['imported'] > 0 || $stats['updated'] > 0) {
            CsvUpload::create([
                'user_id' => $user->id,
                'school_year_id' => $schoolYearId,
                'file_path' => $file->store('csv_uploads'),
                'file_hash' => $fileHash,
            ]);
        }
    }

    /**
     * @param list<ParsedStudentRow> $chunk
     * @param ImportStats $stats
     */
    private function processChunk(array $chunk, string $duplicateAction, array &$stats): void
    {
        /** @var list<string> $serials */
        $serials = array_column($chunk, 'serial_number');

        $existingSerials = $this->getExistingSerials($serials);

        [$toInsert, $toUpdate] = $this->categorizeRecords($chunk, $existingSerials, $duplicateAction, $stats);

        $this->executeBulkOperations($toInsert, $toUpdate, $stats);
    }

    /**
     * @param list<string> $serials
     *
     * @return array<string, int>
     */
    private function getExistingSerials(array $serials): array
    {
        /** @var array<string, int> */
        return Student::whereIn('serial_number', $serials)
            ->pluck('serial_number')
            ->flip()
            ->toArray()
        ;
    }

    /**
     * @param list<ParsedStudentRow> $chunk
     * @param array<string, int> $existingSerials
     * @param ImportStats $stats
     *
     * @return array{0: list<ParsedStudentRow>, 1: list<ParsedStudentRow>}
     */
    private function categorizeRecords(array $chunk, array $existingSerials, string $duplicateAction, array &$stats): array
    {
        $toInsert = [];
        $toUpdate = [];

        foreach ($chunk as $row) {
            if (isset($existingSerials[$row['serial_number']])) {
                if ($duplicateAction === 'update') {
                    $toUpdate[] = $row;
                } else {
                    $stats['skipped']++;
                }
            } else {
                $toInsert[] = $row;
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
                'school_year_id', 'first_name', 'middle_name', 'last_name',
                'course', 'gender', 'birth_date', 'city_address',
                'province_address', 'contact_number', 'email', 'updated_at',
            ];

            Student::upsert($toUpdate, ['serial_number'], $updateColumns);
            $stats['updated'] += \count($toUpdate);
        }
    }

    /* ─── DATA PARSING & CLEANING HELPERS ───────────────────────────────── */

    /**
     * @param array<int, string|null> $row
     * @param array<string, int> $headerMap
     * @param list<string> $errors
     *
     * @return ParsedStudentRow|null
     */
    private function parseRow(array $row, array $headerMap, NstpComponent $component, int $rowNumber, array &$errors): ?array
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
            'contact_number' => $this->cleanValue($this->getValue($row, $headerMap, 'contact')),
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
        return isset($headerMap[$column], $row[$headerMap[$column]]) ? trim((string)$row[$headerMap[$column]]) : null;
    }

    private function cleanValue(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $upper = strtoupper(trim($value));

        return \in_array($upper, ['', 'NI', 'NONE', 'N/A', 'NULL', '-'], true) ? null : trim($value);
    }

    private function cleanEmail(?string $value): ?string
    {
        $cleaned = $this->cleanValue($value);

        return $cleaned && filter_var($cleaned, FILTER_VALIDATE_EMAIL) ? $cleaned : null;
    }

    private function parseGender(?string $value): Gender
    {
        $upper = strtoupper(trim((string) $value));
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
        $cleaned = strtoupper(trim((string) $value));
        $legacyAliases = ['BSHRT' => Course::BSHM->value, 'ABECON' => Course::BSECON->value];
        if (isset($legacyAliases[$cleaned])) {
            return $legacyAliases[$cleaned];
        }
        foreach (Course::cases() as $case) {
            if (strcasecmp($case->value, $cleaned) === 0) {
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
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $cleaned)) {
                return Carbon::createFromFormat('d/m/Y', $cleaned)->format('Y-m-d');
            }

            return Carbon::parse($cleaned)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    private function resolveSchoolYearId(string $schoolYearString): int
    {
        [$start, $end] = explode('-', trim($schoolYearString));

        return SchoolYear::firstOrCreate(['start_year' => (int) $start, 'end_year' => (int) $end])->id;
    }
}
