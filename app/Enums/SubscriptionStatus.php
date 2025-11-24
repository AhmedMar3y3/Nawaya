<?php

namespace App\Enums;

use App\Traits\HasLocalizedEnum;

enum SubscriptionStatus: string
{
    use HasLocalizedEnum;
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case CANCELLED = 'cancelled';
}

