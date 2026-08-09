<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\AuditLog;
use App\Services\AuditLogger;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Auditable
{
    protected ?string $auditMessage = null;

    protected static bool $auditingEnabled = true;

    protected static function bootAuditable(): void
    {
        static::created(function ($model) {
            if ($model->shouldAudit()) {
                AuditLogger::created($model, $model->getAuditMessage());
            }
        });

        static::updated(function ($model) {
            if ($model->shouldAudit()) {
                AuditLogger::updated($model, $model->getAuditMessage());
            }
        });

        static::deleted(function ($model) {
            if ($model->shouldAudit()) {
                AuditLogger::deleted($model, $model->getAuditMessage());
            }
        });
    }

    /**
     * @return MorphMany<AuditLog, $this>
     */
    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    public function setAuditMessage(string $message): self
    {
        $this->auditMessage = $message;

        return $this;
    }

    public function getAuditMessage(): ?string
    {
        $message = $this->auditMessage;
        $this->auditMessage = null;

        return $message;
    }

    public function shouldAudit(): bool
    {
        if (! static::$auditingEnabled) {
            return false;
        }

        if (property_exists($this, 'auditEnabled') && ! $this->auditEnabled) {
            return false;
        }

        return true;
    }

    /**
     * @return list<string>
     */
    public function getAuditExcluded(): array
    {
        $default = [
            'created_at',
            'updated_at',
            'remember_token',
            'password',
        ];

        if (property_exists($this, 'auditExcluded')) {
            return array_merge($default, $this->auditExcluded);
        }

        return $default;
    }
}
