<?php

namespace App\Enums\Subscription;

use App\Traits\HasLocalizedEnum;

enum SubscriptionType: string {
    use HasLocalizedEnum;
    case GIFT   = 'gift';
    case MYSELF = 'myself';
}
