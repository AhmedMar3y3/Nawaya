<?php

namespace App\Enums\Order;

use App\Traits\HasLocalizedEnum;

enum OrderStatus: string {
    use HasLocalizedEnum;

    case PENDING   = 'pending';
    case PAID      = 'paid';
    case COMPLETED = 'completed';
    case FAILED    = 'failed';
    case REFUNDED  = 'refunded';
}
