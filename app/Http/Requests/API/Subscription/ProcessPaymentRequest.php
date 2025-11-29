<?php

namespace App\Http\Requests\API\Subscription;

use App\Http\Requests\BaseRequest;

class ProcessPaymentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'subscription_id' => ['required', 'integer', 'exists:subscriptions,id'],
            'payment_type'    => ['required', 'string', 'in:online,bank_transfer'],
        ];
    }
}

