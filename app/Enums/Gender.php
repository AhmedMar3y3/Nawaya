<?php

namespace App\Enums;

use App\Traits\HasLocalizedEnum;

enum Gender: string {
    use HasLocalizedEnum;

    case MALE   = 'male';
    case FEMALE = 'female';
}
