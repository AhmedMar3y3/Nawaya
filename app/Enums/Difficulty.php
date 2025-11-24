<?php

namespace App\Enums;

use App\Traits\HasLocalizedEnum;

enum Difficulty: string {

    use HasLocalizedEnum;

    case EASY   = 'easy';
    case MEDIUM = 'medium';
    case HARD   = 'hard';

    public function points(): int
    {
        return match ($this) {
            self::EASY   => 1,
            self::MEDIUM => 2,
            self::HARD   => 3,
        };
    }
}
