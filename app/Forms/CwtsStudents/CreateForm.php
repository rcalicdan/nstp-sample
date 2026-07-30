<?php

declare(strict_types=1);

namespace App\Forms\CwtsStudents;

use App\Enums\Gender;
use App\Enums\NstpComponent;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateForm extends Form
{
    public string $serial_number = '';

    public string $last_name = '';

    public string $first_name = '';

    public ?string $middle_name = null;

    public string $course = '';

    public string $gender = '';

    public ?string $birth_date = null;

    public ?string $city_address = null;

    public ?string $province_address = null;

    public ?string $contact_number = null;

    public ?string $email = null;

    public string $school_year = '';

    public function store(): void
    {
        $validated = $this->validate([
            'serial_number' => 'required|string|max:50|unique:students,serial_number',
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'course' => 'required|string|max:50',
            'gender' => ['required', Rule::enum(Gender::class)],
            'birth_date' => 'nullable|date',
            'city_address' => 'nullable|string|max:200',
            'province_address' => 'nullable|string|max:200',
            'contact_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255|unique:students,email',
            'school_year' => 'required|string|regex:/^\d{4}-\d{4}$/',
        ]);

        $validated['school_year_id'] = $this->resolveSchoolYearId($this->school_year);
        $validated['nstp_component'] = NstpComponent::CWTS->value;

        Student::create($validated);

        $this->reset();
    }

    private function resolveSchoolYearId(string $schoolYearString): int
    {
        [$start, $end] = explode('-', $schoolYearString);

        $schoolYear = SchoolYear::firstOrCreate([
            'start_year' => (int) $start,
            'end_year' => (int) $end,
        ]);

        return $schoolYear->id;
    }
}
