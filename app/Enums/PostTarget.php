<?php

declare(strict_types=1);

namespace App\Enums;

enum PostTarget: string
{
    case ALL = 'ALL';
    case CWTS = 'CWTS';
    case ROTC = 'ROTC';
    case LTS = 'LTS';

    public function label(): string
    {
        return match ($this) {
            self::ALL => 'All Components / General',
            self::CWTS => 'CWTS Only',
            self::ROTC => 'ROTC Only',
            self::LTS => 'LTS Only',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }
}
