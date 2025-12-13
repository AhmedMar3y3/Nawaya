<?php

namespace App\Http\Requests\API\Subscription;

use App\Http\Requests\BaseRequest;

class ProcessCharityPaymentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'charity_id'   => ['required', 'integer', 'exists:charities,id'],
            'payment_type' => ['required', 'string', 'in:online,bank_transfer'],
        ];
    }
}
