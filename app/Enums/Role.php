<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    case SUPERADMIN = 'superadmin';
    case ADMIN = 'admin';
    case STAFF = 'staff';

    public function label(): string
    {
        return match($this) {
            self::SUPERADMIN => 'System Administrator',
            self::ADMIN => 'Administrator',
            self::STAFF => 'Staff',
        };
    }
}
