<?php

declare(strict_types=1);

namespace App\Livewire\CwtsStudents;

use App\Enums\Gender;
use App\Enums\NstpComponent;
use App\Livewire\Forms\CwtsStudents\CreateForm;
use App\Livewire\Forms\CwtsStudents\UpdateForm;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Traits\WithToast;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;
    use WithToast;

    public CreateForm $createForm;

    public UpdateForm $updateForm;

    public string $search = '';

    public string $gender = '';

    public string $schoolYear = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
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
