<?php

namespace App\Http\Requests\Admin\Subscription;

use App\Http\Requests\BaseRequest;

class TransferSubscriptionRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'workshop_id' => 'required|exists:workshops,id',
            'package_id' => 'nullable|exists:workshop_packages,id',
            'balance_used' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'workshop_id.required' => 'يرجى اختيار ورشة جديدة',
            'workshop_id.exists' => 'الورشة المحددة غير موجودة',
            'package_id.exists' => 'الباقة المحددة غير موجودة',
            'balance_used.numeric' => 'المبلغ المستخدم من الرصيد يجب أن يكون رقماً',
            'balance_used.min' => 'المبلغ المستخدم من الرصيد يجب أن يكون أكبر من أو يساوي صفر',
            'paid_amount.numeric' => 'المبلغ المدفوع الإضافي يجب أن يكون رقماً',
            'paid_amount.min' => 'المبلغ المدفوع الإضافي يجب أن يكون أكبر من أو يساوي صفر',
            'notes.max' => 'ملاحظات التحويل يجب ألا تتجاوز 1000 حرف',
        ];
    }
}

