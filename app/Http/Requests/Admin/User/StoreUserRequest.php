<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\BaseRequest;

class StoreUserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'full_name'  => ['required', 'string', 'max:255'],
            'phone'      => ['required', 'string', 'unique:users,phone', 'max:20'],
            'email'      => ['nullable', 'email', 'unique:users,email', 'max:255'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'is_active'  => ['nullable', 'boolean'],
        ];
    }

}
