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

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }
}