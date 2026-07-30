<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolYear extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'start_year',
        'end_year',
    ];

    /**
     * @return HasMany<Student, $this>
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * @return HasMany<CsvUpload, $this>
     */
    public function csvUploads(): HasMany
    {
        return $this->hasMany(CsvUpload::class);
    }

    public function getLabelAttribute(): string
    {
        return "{$this->start_year}-{$this->end_year}";
    }
}
