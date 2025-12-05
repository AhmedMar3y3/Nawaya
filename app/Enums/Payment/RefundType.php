<?php

namespace App\Enums\Payment;

use App\Traits\HasLocalizedEnum;

enum RefundType: string {
    use HasLocalizedEnum;

    case CASH          = 'cash';
    case BANK_TRANSFER = 'bank_transfer';
    case LINK          = 'link';
}
