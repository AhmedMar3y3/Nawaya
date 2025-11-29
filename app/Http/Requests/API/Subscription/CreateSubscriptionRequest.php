<?php

namespace App\Http\Requests\API\Subscription;

use App\Http\Requests\BaseRequest;
use App\Enums\Subscription\SubscriptionType;

class CreateSubscriptionRequest extends BaseRequest
{
    public function rules(): array
    {
        $subscriptionType = $this->input('subscription_type');
        
        $rules = [
            'package_id'       => ['required', 'integer', 'exists:workshop_packages,id'],
            'subscription_type' => ['required', 'string', 'in:gift,myself'],
        ];

        // If gift type, require recipient details
        if ($subscriptionType === SubscriptionType::GIFT->value) {
            $rules['recipient_name']  = ['required', 'string', 'max:255'];
            $rules['recipient_phone'] = ['required', 'string', 'max:20'];
            $rules['country_id']      = ['required', 'integer', 'exists:countries,id'];
            $rules['message']         = ['nullable', 'string'];
        }

        return $rules;
    }
}

