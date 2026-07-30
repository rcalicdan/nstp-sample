<?php

declare(strict_types=1);

namespace App\Livewire\CwtsStudents;

use App\Enums\Course;
use App\Enums\Gender;
use App\Enums\NstpComponent;
use App\Forms\CwtsStudents\CreateForm;
use App\Forms\CwtsStudents\UpdateForm;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Services\StudentCsvImportService;
use App\Traits\WithToast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;
    use WithToast;
    use WithFileUploads;

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

            $msg = "Imported: {$result['imported']} | Updated: {$result['updated']} | Skipped: {$result['skipped']}";

            $this->toast('success', $msg);
            $this->reset(['csvFile', 'duplicateAction']);
            $this->dispatch('close-modal', 'create-modal');
        } catch (\Throwable $e) {
            $this->toast('error', $e->getMessage());
        }
    }

    public function store(): void
    {
        $this->createForm->store();

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

        $this->toast('success', 'Student record updated successfully.');
        $this->dispatch('close-modal', 'edit-modal');
    }

    public function deleteStudent(Student $student): void
    {
        $student->delete();
        $this->toast('success', 'Student record removed.');
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'gender', 'schoolYear']);
        $this->resetPage();
    }

    #[Computed]
    public function students()
    {
        return Student::with('schoolYear')
            ->where('nstp_component', NstpComponent::CWTS)
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('last_name', 'ilike', '%' . $this->search . '%')
                        ->orWhere('first_name', 'ilike', '%' . $this->search . '%')
                        ->orWhere('serial_number', 'ilike', '%' . $this->search . '%')
                    ;
                });
            })
            ->when($this->gender, fn (Builder $q) => $q->where('gender', $this->gender))
            ->when($this->schoolYear, function (Builder $q) {
                [$start, $end] = explode('-', $this->schoolYear);
                $q->whereHas('schoolYear', function (Builder $sq) use ($start, $end) {
                    $sq->where('start_year', $start)->where('end_year', $end);
                });
            })
            ->orderBy('last_name')
            ->paginate(20)
        ;
    }

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

    #[Computed]
    public function availableSchoolYears()
    {
        return SchoolYear::orderByDesc('start_year')->get();
    }

    public function render()
    {
        $baseQuery = Student::where('nstp_component', NstpComponent::CWTS);

        return view('livewire.cwts-students.index', [
            'totalStudents' => (clone $baseQuery)->count(),
            'totalMale' => (clone $baseQuery)->where('gender', Gender::MALE->value)->count(),
            'totalFemale' => (clone $baseQuery)->where('gender', Gender::FEMALE->value)->count(),
            'totalCourses' => (clone $baseQuery)->distinct('course')->count('course'),
        ]);
    }
}
