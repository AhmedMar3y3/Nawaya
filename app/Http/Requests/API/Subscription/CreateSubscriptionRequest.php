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
            'package_id'        => ['required', 'integer', 'exists:workshop_packages,id'],
            'subscription_type' => ['required', 'string', 'in:gift,myself'],
        ];

        if ($subscriptionType === SubscriptionType::GIFT->value) {
            $rules['recipient_name']    = ['required', 'array', 'min:1'];
            $rules['recipient_name.*']  = ['required', 'string', 'max:255'];
            $rules['recipient_phone']   = ['required', 'array', 'min:1'];
            $rules['recipient_phone.*'] = ['required', 'string', 'max:20'];
            $rules['country_id']        = ['required', 'array', 'min:1'];
            $rules['country_id.*']      = ['required', 'integer', 'exists:countries,id'];
            // $rules['message']           = ['nullable', 'array'];
            // $rules['message.*']         = ['nullable', 'string'];
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $subscriptionType = $this->input('subscription_type');

            if ($subscriptionType === SubscriptionType::GIFT->value) {
                $recipientPhones = $this->input('recipient_phone', []);

                // if (count($recipientPhones) !== count(array_unique($recipientPhones))) {
                //     $validator->errors()->add('recipient_phone', 'لا يمكن إنشاء عدة هدايا لنفس المستلم');
                // }

                $recipientNames = $this->input('recipient_name', []);
                $countryIds     = $this->input('country_id', []);
                $messages       = $this->input('message', []);

                $count = count($recipientPhones);
                if (count($recipientNames) !== $count || count($countryIds) !== $count) {
                    $validator->errors()->add('recipient_phone', 'يجب أن يكون عدد العناصر متساوياً في جميع الحقول');
                }
            }
        });
    }
}
