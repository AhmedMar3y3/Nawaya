<?php

namespace App\Http\Requests\API\Subscription;

use App\Http\Requests\BaseRequest;

class ProcessPaymentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'subscription_id'    => ['required_without:subscription_ids', 'integer', 'exists:subscriptions,id'],
            'subscription_ids'   => ['required_without:subscription_id', 'array', 'min:1'],
            'subscription_ids.*' => ['required', 'integer', 'exists:subscriptions,id'],
            'payment_type'       => ['required', 'string', 'in:online,bank_transfer'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $subscriptionIds = $this->input('subscription_ids', []);

            if (! empty($subscriptionIds) && count($subscriptionIds) !== count(array_unique($subscriptionIds))) {
                $validator->errors()->add('subscription_ids', 'يجب أن تكون معرفات الاشتراكات فريدة');
            }
        });
    }
}
