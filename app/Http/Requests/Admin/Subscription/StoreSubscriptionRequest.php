<?php

namespace App\Http\Requests\Admin\Subscription;

use App\Http\Requests\Admin\BaseAdminRequest;
use App\Enums\Payment\PaymentType;

class StoreSubscriptionRequest extends BaseAdminRequest
{
    public function rules(): array
    {
        $paymentTypes = implode(',', array_column(PaymentType::cases(), 'value'));

        return [
            // User selection - either existing user or new user fields
            'user_id' => ['nullable', 'required_without:full_name', 'exists:users,id'],
            'full_name' => ['required_without:user_id', 'nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required_without:user_id', 'nullable', 'string', 'max:255'],
            'country_id' => ['nullable', 'exists:countries,id'],

            // Subscription details
            'workshop_id' => ['required', 'exists:workshops,id'],
            'package_id' => ['nullable', 'exists:workshop_packages,id'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'balance_used' => ['nullable', 'numeric', 'min:0'],
            'payment_type' => ['required', 'string', 'in:' . $paymentTypes],
            'transferer_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required_without' => 'يرجى اختيار مستفيد أو إدخال بيانات مستفيد جديد',
            'full_name.required_without' => 'الاسم الكامل مطلوب عند إنشاء مستفيد جديد',
            'phone.required_without' => 'رقم الهاتف مطلوب عند إنشاء مستفيد جديد',
            'workshop_id.required' => 'يرجى اختيار ورشة',
            'workshop_id.exists' => 'الورشة المختارة غير موجودة',
            'package_id.exists' => 'الباقة المختارة غير موجودة',
            'paid_amount.required' => 'المبلغ المدفوع مطلوب',
            'paid_amount.numeric' => 'المبلغ المدفوع يجب أن يكون رقماً',
            'paid_amount.min' => 'المبلغ المدفوع يجب أن يكون أكبر من أو يساوي صفر',
            'balance_used.numeric' => 'المبلغ المستخدم من الرصيد يجب أن يكون رقماً',
            'balance_used.min' => 'المبلغ المستخدم من الرصيد يجب أن يكون أكبر من أو يساوي صفر',
            'payment_type.required' => 'يرجى اختيار طريقة الدفع',
            'payment_type.in' => 'طريقة الدفع المختارة غير صحيحة',
            'country_id.exists' => 'الدولة المختارة غير موجودة',
        ];
    }

    protected function prepareForValidation(): void
    {
        // If user_id is provided, remove new user fields
        if ($this->has('user_id') && $this->user_id) {
            $this->merge([
                'full_name' => null,
                'email' => null,
                'phone' => null,
                'country_id' => null,
            ]);
            
            // Validate balance_used doesn't exceed user balance
            if ($this->has('balance_used') && $this->balance_used > 0) {
                $user = \App\Models\User::find($this->user_id);
                if ($user && $this->balance_used > $user->balance) {
                    $this->merge(['balance_used' => 0]);
                }
            }
        } else {
            // If creating new user, balance_used should be 0
            $this->merge(['balance_used' => 0]);
        }
    }
}

