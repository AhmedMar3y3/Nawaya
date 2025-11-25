<?php

namespace App\Enums\Boutique;

use App\Traits\HasLocalizedEnum;

enum OwnerType: string {
    use HasLocalizedEnum;

    case PLATFORM = 'platform';
    case USER = 'user';
}
