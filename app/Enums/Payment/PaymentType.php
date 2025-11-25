<?php
namespace App\Enums\Payment;

use App\Traits\HasLocalizedEnum;

enum PaymentType: string {
    use HasLocalizedEnum;

    case CASH          = 'cash';
    case ONLINE        = 'online';
    case BANK_TRANSFER = 'bank_transfer';
}
