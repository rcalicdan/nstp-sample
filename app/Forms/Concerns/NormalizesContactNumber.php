<?php

declare(strict_types=1);

namespace App\Forms\Concerns;

use Illuminate\Support\Str;

trait NormalizesContactNumber
{
    protected function normalizeContactNumber(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $digits = Str::of($value)->replaceMatches('/\D/', '');

        if ($digits->isEmpty()) {
            return null;
        }

        if ($digits->startsWith('639') && $digits->length() >= 12) {
            return (string) $digits->substr(2, 10)->prepend('0');
        }

        if ($digits->startsWith('09')) {
            return (string) $digits->substr(0, 11);
        }

        if ($digits->startsWith('9')) {
            return (string) $digits->prepend('0')->substr(0, 11);
        }

        return $value;
    }
}
