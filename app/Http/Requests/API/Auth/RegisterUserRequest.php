<?php

namespace App\Http\Requests\API\Auth;

use App\Http\Requests\BaseRequest;

class RegisterUserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'full_name'  => 'required|string|max:255|regex:/^\S+(\s+\S+)+$/',
            'country_id' => 'required|exists:countries,id',
            'email'      => 'required|email|max:255|unique:users,email',
            'phone'      => 'required|string|max:20|unique:users,phone',

        ];
    }
}
