<?php

namespace App\Enums\Settings;

use App\Traits\HasLocalizedEnum;

enum DrHope: string {
    use HasLocalizedEnum;

    case INSTAGRAM = 'instagram';
    case IMAGE     = 'image';
    case VIDEO     = 'video';
}
