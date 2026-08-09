<?php

declare(strict_types=1);

namespace App\Livewire\CwtsStudents;

use App\Enums\Course;
use App\Enums\Gender;
use App\Enums\NstpComponent;
use App\Forms\CwtsStudents\CreateForm;
use App\Forms\CwtsStudents\UpdateForm;
use App\Models\CsvUpload;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Services\StudentCsvImportService;
use App\Traits\WithToast;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

/**
 * @phpstan-type StatTotals array{totalStudents: int, totalMale: int, totalFemale: int, totalCourses: int}
 * @phpstan-type OptionObject object{value: string, label: string}
 */
#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads;
    use WithPagination;
    use WithToast;

    private const string STATS_CACHE_KEY = 'nstp_stats_cwts';

    public CreateForm $createForm;

    public UpdateForm $updateForm;

    public $csvFile = null;

    public string $duplicateAction = 'skip';

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $gender = '';

    #[Url(except: '')]
    public string $schoolYear = '';

    public function updated($property): void
    {
        if (\in_array($property, ['search', 'gender', 'schoolYear'], true)) {
            $this->resetPage();
        }
    }

    public function importCsv(StudentCsvImportService $service): void
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:10240',
            'duplicateAction' => 'required|in:skip,update',
        ]);

        try {
            $result = $service->import(
                file: $this->csvFile,
                user: auth()->user(),
                component: NstpComponent::CWTS,
                duplicateAction: $this->duplicateAction
            );

            $this->clearStatsCache();

            $msg = "Imported: {$result['imported']} | Updated: {$result['updated']} | Skipped: {$result['skipped']}";

            $this->toast('success', $msg);
            $this->reset(['csvFile', 'duplicateAction']);
            $this->dispatch('close-modal', 'create-modal');
        } catch (\Throwable $e) {
            $this->toast('error', $e->getMessage());
        }
    }

    public function rollbackCsv(CsvUpload $csvUpload, StudentCsvImportService $service): void
    {
        $service->rollback($csvUpload);
        $this->clearStatsCache();

        $this->toast('success', "CSV Import '{$csvUpload->file_name}' rolled back successfully.");
    }

    public function store(): void
    {
        $this->createForm->store();
        $this->clearStatsCache();
        $this->toast('success', 'New student added successfully.');
        $this->dispatch('close-modal', 'create-modal');
    }

    public function editStudent(Student $student): void
    {
        $this->updateForm->setStudent($student);
        $this->resetValidation();
        $this->dispatch('open-modal', 'edit-modal');
    }

    public function update(): void
    {
        $this->updateForm->update();
        $this->clearStatsCache();
        $this->toast('success', 'Student record updated successfully.');
        $this->dispatch('close-modal', 'edit-modal');
    }

    public function deleteStudent(Student $student): void
    {
        $student->delete();
        $this->clearStatsCache();
        $this->toast('success', 'Student record removed.');
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'gender', 'schoolYear']);
        $this->resetPage();
    }

    /**
     * @return LengthAwarePaginator<Student>
     */
    #[Computed]
    public function students(): LengthAwarePaginator
    {
        return Student::with('schoolYear')
            ->where('nstp_component', NstpComponent::CWTS)
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('last_name', 'ilike', '%' . $this->search . '%')
                        ->orWhere('first_name', 'ilike', '%' . $this->search . '%')
                        ->orWhere('serial_number', 'ilike', '%' . $this->search . '%');
                });
            })
            ->when($this->gender, fn (Builder $q) => $q->where('gender', $this->gender))
            ->when($this->schoolYear, function (Builder $q) {
                if (str_contains($this->schoolYear, '-')) {
                    [$start, $end] = explode('-', $this->schoolYear);
                    $q->whereHas('schoolYear', function (Builder $sq) use ($start, $end) {
                        $sq->where('start_year', (int) $start)->where('end_year', (int) $end);
                    });
                }
            })
            ->orderBy('last_name')
            ->paginate(20);
    }

    /**
     * @return StatTotals
     */
    #[Computed]
    public function stats(): array
    {
        return Cache::rememberForever(self::STATS_CACHE_KEY, function () {
            $baseQuery = Student::where('nstp_component', NstpComponent::CWTS);

            return [
                'totalStudents' => (clone $baseQuery)->count(),
                'totalMale' => (clone $baseQuery)->where('gender', Gender::MALE->value)->count(),
                'totalFemale' => (clone $baseQuery)->where('gender', Gender::FEMALE->value)->count(),
                'totalCourses' => (clone $baseQuery)->distinct('course')->count('course'),
            ];
        });
    }

    /**
     * @return Collection<int, CsvUpload>
     */
    #[Computed]
    public function recentUploads(): Collection
    {
        return CsvUpload::with(['user', 'schoolYear'])
            ->where('nstp_component', NstpComponent::CWTS)
            ->latest()
            ->take(10)
            ->get();
    }

    /**
     * @return list<OptionObject>
     */
    #[Computed]
    public function courseOptions(): array
    {
        return array_map(function (Course $course) {
            return (object) [
                'value' => $course->value,
                'label' => $course->value . ' - ' . Str::limit($course->label(), 42),
            ];
        }, Course::cases());
    }

    /**
     * @return list<OptionObject>
     */
    #[Computed]
    public function genderOptions(): array
    {
        return array_map(function (Gender $gender) {
            return (object) [
                'value' => $gender->value,
                'label' => $gender->value,
            ];
        }, Gender::cases());
    }

    /**
     * @return Collection<int, SchoolYear>
     */
    #[Computed]
    public function availableSchoolYears(): Collection
    {
        return SchoolYear::orderByDesc('start_year')->get();
    }

    public function render()
    {
        return view('livewire.cwts-students.index', [
            'totalStudents' => $this->stats['totalStudents'],
            'totalMale' => $this->stats['totalMale'],
            'totalFemale' => $this->stats['totalFemale'],
            'totalCourses' => $this->stats['totalCourses'],
        ]);
    }

    private function clearStatsCache(): void
    {
        Cache::forget(self::STATS_CACHE_KEY);
    }
}