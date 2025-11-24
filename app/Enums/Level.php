<?php

namespace App\Enums;

use App\Traits\HasLocalizedEnum;

enum Level: string {
    use HasLocalizedEnum;

    case BEGINNER     = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED     = 'advanced';

    public static function fromScore(int $score): self
    {
        if ($score <= 12) {
            return self::BEGINNER;
        }

        if ($score <= 24) {
            return self::INTERMEDIATE;
        }

        return self::ADVANCED;
    }
}
