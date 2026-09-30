<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PostImage extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'disk',
        'file_path',
        'original_name',
        'file_size',
        'mime_type',
        'temp_token',
    ];

    protected static function booted(): void
    {
        static::deleting(function (PostImage $image) {
            if (Storage::disk($image->disk)->exists($image->file_path)) {
                Storage::disk($image->disk)->delete($image->file_path);
            }
        });
    }

    /**
     * @return BelongsTo<Post, $this>
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->file_path);
    }
}