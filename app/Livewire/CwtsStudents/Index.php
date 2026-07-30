<?php

namespace App\Livewire\CwtsStudents;

use App\Traits\WithToast;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithToast;

    public string $search = '';
    public string $gender = '';
    public string $schoolYear = '';

    /**
     * @return array<int, array<string, mixed>>
     */
    private function getMockStudents(): array
    {
        return [
            [
                'id' => 1,
                'serial_number' => 'C-24-000001-01',
                'last_name' => 'Santos',
                'first_name' => 'Maria',
                'middle_name' => 'Cruz',
                'course' => 'BSIT',
                'gender' => 'Female',
                'school_year' => '2024-2025',
                'contact_number' => '09171234501',
                'email' => 'maria.santos@evsu.edu.ph',
                'birth_date' => '2004-03-12',
                'city_address' => 'Tacloban City',
                'province_address' => 'Leyte',
            ],
            [
                'id' => 2,
                'serial_number' => 'C-24-000002-01',
                'last_name' => 'Dela Cruz',
                'first_name' => 'Juan',
                'middle_name' => 'Reyes',
                'course' => 'BSCS',
                'gender' => 'Male',
                'school_year' => '2024-2025',
                'contact_number' => '09181234502',
                'email' => 'juan.delacruz@evsu.edu.ph',
                'birth_date' => '2003-11-25',
                'city_address' => 'Palo',
                'province_address' => 'Leyte',
            ],
            [
                'id' => 3,
                'serial_number' => 'C-24-000003-01',
                'last_name' => 'Gonzales',
                'first_name' => 'Ana',
                'middle_name' => 'Luz',
                'course' => 'BSED',
                'gender' => 'Female',
                'school_year' => '2023-2024',
                'contact_number' => '09191234503',
                'email' => 'ana.gonzales@evsu.edu.ph',
                'birth_date' => '2004-01-15',
                'city_address' => 'Ormoc City',
                'province_address' => 'Leyte',
            ],
        ];
    }

    public function deleteStudent(int $id): void
    {
        $this->toast('success', 'Student record deleted successfully.');
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'gender', 'schoolYear']);
    }

    public function render()
    {
        return view('livewire.cwts-students.index', [
            'students' => $this->getMockStudents(),
            'totalStudents' => 150,
            'totalMale' => 80,
            'totalFemale' => 70,
            'totalCourses' => 6,
            'schoolYears' => ['2024-2025', '2023-2024', '2022-2023'],
        ]);
    }
}
