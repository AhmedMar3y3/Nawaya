<?php

namespace App\Enums;

use App\Traits\HasLocalizedEnum;

enum Section: string {
    use HasLocalizedEnum;

    case LITERAL    = 'literal';
    case SCIENTIFIC = 'scientific';
}
