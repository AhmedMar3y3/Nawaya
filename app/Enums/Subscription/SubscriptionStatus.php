<?php
namespace App\Enums\Subscription;

use App\Traits\HasLocalizedEnum;

enum SubscriptionStatus: string {
    use HasLocalizedEnum;
    case PENDING       = 'pending';
    case PROCESSING    = 'processing';
    case PAID          = 'paid';
    case ACTIVE        = 'active';
    case EXPIRED       = 'expired';
    case FAILED        = 'failed';
    case REFUNDED      = 'refunded';
    case USER_BALANCE  = 'user_balance';
}
