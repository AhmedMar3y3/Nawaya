<?php

namespace App\Http\Requests\Admin\Profile;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseRequest;

class UpdateProfileRequest extends BaseRequest
{
    public function rules(): array
    {
        $admin = $this->user('admin');

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar'   => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'اسم المدير مطلوب',
            'email.required'     => 'البريد الإلكتروني مطلوب',
            'email.email'        => 'البريد الإلكتروني غير صحيح',
            'email.unique'       => 'البريد الإلكتروني مستخدم بالفعل',
            'password.min'       => 'كلمة المرور يجب أن تكون على الأقل 8 أحرف',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'avatar.image'       => 'يجب أن يكون الملف صورة',
            'avatar.mimes'       => 'نوع الصورة يجب أن يكون: jpeg, png, jpg',
            'avatar.max'         => 'حجم الصورة يجب أن يكون أقل من 5 ميجابايت',
        ];
    }
}
