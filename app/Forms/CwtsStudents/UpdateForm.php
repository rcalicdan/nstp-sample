<?php

declare(strict_types=1);

namespace App\Forms\CwtsStudents;

use App\Enums\Gender;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateForm extends Form
{
    public ?Student $student = null;

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

    public function rules(): array
    {
        return [
            'serial_number' => ['required', 'string', 'max:50', Rule::unique('students')->ignore($this->student?->id)],
            'last_name' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'course' => ['required', 'string', 'max:50'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'birth_date' => ['nullable', 'date_format:Y-m-d'],
            'city_address' => ['nullable', 'string', 'max:200'],
            'province_address' => ['nullable', 'string', 'max:200'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('students')->ignore($this->student?->id)],
            'school_year' => ['required', 'string', 'regex:/^\d{4}-\d{4}$/'],
        ];
    }

    public function setStudent(Student $student): void
    {
        $this->student = $student;
        $this->serial_number = $student->serial_number;
        $this->last_name = $student->last_name;
        $this->first_name = $student->first_name;
        $this->middle_name = $student->middle_name;
        $this->course = $student->course;
        $this->gender = $student->gender->value;
        $this->birth_date = $student->birth_date?->format('Y-m-d');
        $this->city_address = $student->city_address;
        $this->province_address = $student->province_address;
        $this->contact_number = $student->contact_number;
        $this->email = $student->email;
        $this->school_year = $student->schoolYear ? $student->schoolYear->label : '';
    }

    public function update(): void
    {
        $validated = $this->validate();

        $validated['school_year_id'] = $this->resolveSchoolYearId($this->school_year);

        $this->student->update($validated);

        $this->reset();
    }

    private function resolveSchoolYearId(string $schoolYearString): int
    {
        [$start, $end] = explode('-', $schoolYearString);

        return SchoolYear::firstOrCreate([
            'start_year' => (int) $start,
            'end_year' => (int) $end,
        ])->id;
    }
}
