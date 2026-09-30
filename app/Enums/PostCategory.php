<?php

declare(strict_types=1);

namespace App\Enums;

enum PostCategory: string
{
    case NEWS = 'News';
    case ANNOUNCEMENT = 'Announcement';
    case EVENT = 'Event';
    case ADVISORY = 'Advisory';

    public function label(): string
    {
        return $this->value;
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::NEWS => 'bg-blue-50 text-blue-700 border-blue-200',
            self::ANNOUNCEMENT => 'bg-amber-50 text-amber-700 border-amber-200',
            self::EVENT => 'bg-purple-50 text-purple-700 border-purple-200',
            self::ADVISORY => 'bg-red-50 text-red-700 border-red-200',
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
