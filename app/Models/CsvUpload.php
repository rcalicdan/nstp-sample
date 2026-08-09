<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NstpComponent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CsvUpload extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'school_year_id',
        'nstp_component',
        'file_name',
        'file_path',
        'file_hash',
        'imported_count',
        'updated_count',
    ];

    /**
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            'nstp_component' => NstpComponent::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<SchoolYear, $this>
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    /**
     * @return HasMany<Student, $this>
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
