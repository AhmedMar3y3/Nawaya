<?php

namespace App\Http\Requests\Admin\Subscription;

use App\Http\Requests\Admin\BaseAdminRequest;
use App\Enums\Payment\PaymentType;

class UpdateSubscriptionRequest extends BaseAdminRequest
{
    public function rules(): array
    {
        $paymentTypes = implode(',', array_column(PaymentType::cases(), 'value'));

        return [
            'package_id' => ['nullable', 'exists:workshop_packages,id'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'payment_type' => ['required', 'string', 'in:' . $paymentTypes],
            'transferer_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'package_id.exists' => 'الباقة المختارة غير موجودة',
            'paid_amount.required' => 'المبلغ المدفوع مطلوب',
            'paid_amount.numeric' => 'المبلغ المدفوع يجب أن يكون رقماً',
            'paid_amount.min' => 'المبلغ المدفوع يجب أن يكون أكبر من أو يساوي صفر',
            'payment_type.required' => 'يرجى اختيار طريقة الدفع',
            'payment_type.in' => 'طريقة الدفع المختارة غير صحيحة',
        ];
    }
}


