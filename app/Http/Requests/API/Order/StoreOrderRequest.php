<?php

namespace App\Http\Requests\API\Order;

use App\Enums\Payment\PaymentType;
use App\Models\Setting;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Validator;

class StoreOrderRequest extends BaseRequest
{
    public function rules(): array
    {
        $validPayTypes = array_column(PaymentType::cases(), 'value');

        return [
            'payment_type' => [
                'required',
                'in:' . implode(',', $validPayTypes),
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $paymentType = $this->input('payment_type');

            if (! $paymentType) {
                return;
            }

            $settings = Setting::whereIn('key', [
                'online_payment',
                'bank_transfer',
            ])->pluck('value', 'key');

            $onlinePaymentEnabled = filter_var($settings['online_payment'] ?? true, FILTER_VALIDATE_BOOLEAN);
            $bankTransferEnabled = filter_var($settings['bank_transfer'] ?? true, FILTER_VALIDATE_BOOLEAN);

            if ($paymentType === PaymentType::ONLINE->value && ! $onlinePaymentEnabled) {
                $validator->errors()->add('payment_type', 'الدفع الإلكتروني غير متاح حالياً');
            }

            if ($paymentType === PaymentType::BANK_TRANSFER->value && ! $bankTransferEnabled) {
                $validator->errors()->add('payment_type', 'التحويل البنكي غير متاح حالياً');
            }
        });
    }
}
