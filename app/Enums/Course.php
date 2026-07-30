<?php

declare(strict_types=1);

namespace App\Enums;

enum Course: string
{
    case BSAR = 'BSAr';
    case BSID = 'BSID';
    case BSECON = 'BSEcon';
    case BSFIL = 'BSFil';
    case BAEL = 'BAEL';
    case BSMATH = 'BSMath';
    case BSES = 'BSES';
    case BSCHEM = 'BSChem';
    case BSSTAT = 'BSStat';
    case BHUMSERV = 'BHumServ';
    case BSE = 'BSE';
    case BSOA = 'BSOA';
    case BSA = 'BSA';
    case BSM = 'BSM';
    case BSED_MATH = 'BSEd-Math';
    case BSED_SCI = 'BSEd-Sci';
    case BCAED = 'BCAEd';
    case BPED = 'BPEd';
    case BEED = 'BEED';
    case BTVTED_FSM = 'BTVTEd-FSM';
    case BTVTED_CC = 'BTVTEd-CC';
    case BTVTED_AT = 'BTVTEd-AT';
    case BTVTED_ET = 'BTVTEd-ET';
    case BTVTED_GFD = 'BTVTEd-GFD';
    case BTVTED_HVACR = 'BTVTEd-HVACR';
    case BTLED_IA = 'BTLEd-IA';
    case BTLED_HE = 'BTLEd-HE';
    case DTS = 'DTS';
    case BSCHE = 'BSChE';
    case BSCE = 'BSCE';
    case BSEE = 'BSEE';
    case BSECE = 'BSECE';
    case BSGE = 'BSGE';
    case BSME = 'BSME';
    case BSIE = 'BSIE';
    case BSIT = 'BSIT';
    case BSHM = 'BSHM';
    case BSND = 'BSND';
    case BINDTECH = 'BIndTech';
    case BSMT_AUTO = 'BSMT-Auto';
    case BSMT_METAL = 'BSMT-Metal';
    case BSMT_MS = 'BSMT-MS';
    case BSMT_WF = 'BSMT-WF';

    public function label(): string
    {
        return match ($this) {
            self::BSAR => 'Bachelor of Science in Architecture',
            self::BSID => 'Bachelor of Science in Interior Design',

            self::BSECON => 'Bachelor of Science in Economics',
            self::BSFIL => 'Batsilyer ng Sining sa Filipino',
            self::BAEL => 'Bachelor of Arts in English Language',
            self::BSMATH => 'Bachelor of Science in Mathematics',
            self::BSES => 'Bachelor of Science in Environmental Science',
            self::BSCHEM => 'Bachelor of Science in Chemistry',
            self::BSSTAT => 'Bachelor of Science in Statistics',
            self::BHUMSERV => 'Bachelor in Human Services',

            self::BSE => 'Bachelor of Science in Entrepreneurship',
            self::BSOA => 'Bachelor of Science in Office Administration',
            self::BSA => 'Bachelor of Science in Accountancy',
            self::BSM => 'Bachelor of Science in Marketing',

            self::BSED_MATH => 'Bachelor of Secondary Education (Mathematics)',
            self::BSED_SCI => 'Bachelor of Secondary Education (Science)',
            self::BCAED => 'Bachelor of Culture & Arts Education',
            self::BPED => 'Bachelor of Physical Education',
            self::BEED => 'Bachelor in Elementary Education',
            self::BTVTED_FSM => 'Bachelor of Technical-Vocational Teacher Education (FSM)',
            self::BTVTED_CC => 'Bachelor of Technical-Vocational Teacher Education (Civil & Construction)',
            self::BTVTED_AT => 'Bachelor of Technical-Vocational Teacher Education (Automotive)',
            self::BTVTED_ET => 'Bachelor of Technical-Vocational Teacher Education (Electrical)',
            self::BTVTED_GFD => 'Bachelor of Technical-Vocational Teacher Education (GFD)',
            self::BTVTED_HVACR => 'Bachelor of Technical-Vocational Teacher Education (HVACR)',
            self::BTLED_IA => 'Bachelor of Technology & Livelihood Education (IA)',
            self::BTLED_HE => 'Bachelor of Technology & Livelihood Education (HE)',
            self::DTS => 'Diploma in Teaching Secondary',

            self::BSCHE => 'Bachelor of Science in Chemical Engineering',
            self::BSCE => 'Bachelor of Science in Civil Engineering',
            self::BSEE => 'Bachelor of Science in Electrical Engineering',
            self::BSECE => 'Bachelor of Science in Electronics Engineering',
            self::BSGE => 'Bachelor of Science in Geodetic Engineering',
            self::BSME => 'Bachelor of Science in Mechanical Engineering',
            self::BSIE => 'Bachelor of Science in Industrial Engineering',
            self::BSIT => 'Bachelor of Science in Information Technology',

            self::BSHM => 'Bachelor of Science in Hospitality Management',
            self::BSND => 'Bachelor of Science in Nutrition & Dietetics',
            self::BINDTECH => 'Bachelor of Industrial Technology',
            self::BSMT_AUTO => 'Bachelor of Science in Mechanical Technology (Automotive)',
            self::BSMT_METAL => 'Bachelor of Science in Mechanical Technology (Metallurgy)',
            self::BSMT_MS => 'Bachelor of Science in Mechanical Technology (Machine Shop)',
            self::BSMT_WF => 'Bachelor of Science in Mechanical Technology (Welding & Fabrication)',
        };
    }

    public function school(): string
    {
        return match ($this) {
            self::BSAR, self::BSID => 'School of Architecture and Allied Disciplines (SAAD)',
            self::BSECON, self::BSFIL, self::BAEL, self::BSMATH, self::BSES, self::BSCHEM, self::BSSTAT, self::BHUMSERV => 'School of Arts and Sciences (SAS)',
            self::BSE, self::BSOA, self::BSA, self::BSM => 'School of Accountancy, Management and Entrepreneurship (SAME)',
            self::BSED_MATH, self::BSED_SCI, self::BCAED, self::BPED, self::BEED, self::BTVTED_FSM, self::BTVTED_CC, self::BTVTED_AT, self::BTVTED_ET, self::BTVTED_GFD, self::BTVTED_HVACR, self::BTLED_IA, self::BTLED_HE, self::DTS => 'School of Education (SOED)',
            self::BSCHE, self::BSCE, self::BSEE, self::BSECE, self::BSGE, self::BSME, self::BSIE, self::BSIT => 'School of Engineering (SOE)',
            self::BSHM, self::BSND, self::BINDTECH, self::BSMT_AUTO, self::BSMT_METAL, self::BSMT_MS, self::BSMT_WF => 'School of Technology (SOT)',
        };
    }

    public static function getLabel(string $code): string
    {
        $legacyAliases = [
            'BSHRT' => 'Bachelor of Science in Hospitality Management (BSHM)',
            'ABECON' => 'Bachelor of Science in Economics (BSEcon)',
        ];

        $cleanCode = strtoupper(trim($code));

        if (isset($legacyAliases[$cleanCode])) {
            return $legacyAliases[$cleanCode];
        }

        foreach (self::cases() as $case) {
            if (strcasecmp($case->value, $code) === 0) {
                return $case->label();
            }
        }

        return strtoupper($code);
    }
}
