<?php

namespace App\Enums;

use App\Traits\HasLocalizedEnum;

enum QuestionKind: string {
    use HasLocalizedEnum;

    case VERBAL       = 'verbal';
    case QUANTITATIVE = 'quantitative';
}
