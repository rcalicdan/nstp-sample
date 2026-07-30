<?php

declare(strict_types=1);

namespace App\Traits;

trait WithToast
{
    public function toast(string $type, string $message): void
    {
        $this->dispatch('notify', type: $type, message: $message);
    }
}
