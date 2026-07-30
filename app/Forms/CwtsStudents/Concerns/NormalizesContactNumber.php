<?php

declare(strict_types=1);

namespace App\Forms\CwtsStudents\Concerns;

trait NormalizesContactNumber
{
    public function updatedContactNumber(string $value): void
    {
        $this->contact_number = preg_replace('/[\s\-()]/', '', $value);
    }
}