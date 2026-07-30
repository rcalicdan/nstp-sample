<?php

declare(strict_types=1);

namespace App\Enums;

enum NstpComponent: string
{
    case CWTS = 'CWTS';
    case ROTC = 'ROTC';
    case LTS = 'LTS';

    public function label(): string
    {
        return match($this) {
            self::CWTS => 'Civic Welfare Training Service',
            self::ROTC => 'Reserve Officers\' Training Corps',
            self::LTS => 'Literacy Training Service',
        };
    }
}
