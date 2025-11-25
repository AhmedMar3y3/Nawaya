<?php

namespace App\Enums\Workshop;

use App\Traits\HasLocalizedEnum;

enum WorkshopType: string {
    use HasLocalizedEnum;

    case ONLINE        = 'online';
    case ONSITE        = 'onsite';
    case ONLINE_ONSITE = 'online_onsite';
    case RECORDED      = 'recorded';
}
