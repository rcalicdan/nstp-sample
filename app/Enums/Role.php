<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    case SUPERADMIN = 'superadmin';
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case STAFF = 'staff';

    public function label(): string
    {
        return match($this) {
            self::SUPERADMIN => 'System Administrator',
            self::ADMIN => 'Administrator',
            self::EDITOR => 'Content Editor',
            self::STAFF => 'Staff',
        };
    }
}
