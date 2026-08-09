<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    /**
     * @var list<string>
     */
    protected static array $excludedAttributes = [
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'password',
        'password_confirmation',
    ];

    protected static ?string $customMessage = null;

    /**
     * @var array<string, mixed>
     */
    protected static array $additionalData = [];

    public static function created(Model $model, ?string $message = null): void
    {
        static::log($model, 'created', [], $model->getAttributes(), $message);
    }

    public static function updated(Model $model, ?string $message = null): void
    {
        $originalData = $model->getOriginal();
        $newData = $model->getAttributes();

        $changes = static::getChanges($originalData, $newData);

        if (! empty($changes['old']) || ! empty($changes['new'])) {
            static::log($model, 'updated', $changes['old'], $changes['new'], $message);
        }
    }

    public static function deleted(Model $model, ?string $message = null): void
    {
        static::log($model, 'deleted', $model->getOriginal(), [], $message);
    }

    /**
     * @param array<string, mixed> $oldValues
     * @param array<string, mixed> $newValues
     */
    public static function custom(Model $model, string $event, array $oldValues = [], array $newValues = [], ?string $message = null): void
    {
        static::log($model, $event, $oldValues, $newValues, $message);
    }

    /**
     * @param array<string, mixed> $oldValues
     * @param array<string, mixed> $newValues
     */
    protected static function log(Model $model, string $event, array $oldValues, array $newValues, ?string $message = null): void
    {
        $finalMessage = $message ?? static::$customMessage;
        $excludedAttributes = static::getExcludedAttributesForModel($model);

        $oldValues = static::filterAttributes($oldValues, $excludedAttributes);
        $newValues = static::filterAttributes($newValues, $excludedAttributes);

        AuditLog::create([
            'auditable_type' => \get_class($model),
            'auditable_id' => $model->getKey(),
            'event' => $event,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'message' => $finalMessage,
            'user_id' => Auth::id(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
        ]);

        static::$customMessage = null;
        static::$additionalData = [];
    }

    /**
     * @return list<string>
     */
    protected static function getExcludedAttributesForModel(Model $model): array
    {
        if (method_exists($model, 'getAuditExcluded')) {
            return $model->getAuditExcluded();
        }

        if (property_exists($model, 'auditExcluded')) {
            return array_merge(static::$excludedAttributes, $model->auditExcluded);
        }

        return static::$excludedAttributes;
    }

    /**
     * @param array<string, mixed> $original
     * @param array<string, mixed> $current
     *
     * @return array{old: array<string, mixed>, new: array<string, mixed>}
     */
    protected static function getChanges(array $original, array $current): array
    {
        $oldValues = [];
        $newValues = [];

        foreach ($current as $key => $value) {
            if (\array_key_exists($key, $original) && $original[$key] !== $value) {
                $oldValues[$key] = $original[$key];
                $newValues[$key] = $value;
            }
        }

        return ['old' => $oldValues, 'new' => $newValues];
    }

    /**
     * @param array<string, mixed> $attributes
     * @param list<string>|null $excludedAttributes
     *
     * @return array<string, mixed>
     */
    protected static function filterAttributes(array $attributes, ?array $excludedAttributes = null): array
    {
        $excluded = $excludedAttributes ?? static::$excludedAttributes;

        return array_diff_key($attributes, array_flip($excluded));
    }
}
