<?php

namespace App\Enums\Payment;

use App\Traits\HasLocalizedEnum;

enum PaymentType: string {
    use HasLocalizedEnum;

    case ONLINE        = 'online';
    case BANK_TRANSFER = 'bank_transfer';
    case LINK          = 'link';
    case USER_BALANCE  = 'user_balance';
    case CHARITY       = 'charity';
}
