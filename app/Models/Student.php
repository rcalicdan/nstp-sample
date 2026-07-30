<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Course;
use App\Enums\Gender;
use App\Enums\NstpComponent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'school_year_id',
        'nstp_component',
        'serial_number',
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
    ];

    /**
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            'nstp_component' => NstpComponent::class,
            'gender' => Gender::class,
            'birth_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<SchoolYear, $this>
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function getCourseLabelAttribute(): string
    {
        return Course::getLabel($this->course);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->last_name}, {$this->first_name} {$this->middle_name}");
    }
}
