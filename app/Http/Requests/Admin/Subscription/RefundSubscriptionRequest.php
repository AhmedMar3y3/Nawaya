<?php

namespace App\Http\Requests\Admin\Subscription;

use App\Http\Requests\BaseRequest;
use App\Enums\Payment\RefundType;

class RefundSubscriptionRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'refund_type' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!in_array($value, array_column(RefundType::cases(), 'value'))) {
                    $fail('نوع الاسترداد المحدد غير صحيح.');
                }
            }],
            'refund_notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'refund_type.required' => 'يرجى اختيار طريقة الاسترداد',
            'refund_notes.max' => 'ملاحظات الاسترداد يجب ألا تتجاوز 1000 حرف',
        ];
    }
}

