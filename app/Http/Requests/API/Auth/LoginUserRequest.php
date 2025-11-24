<?php

namespace App\Http\Requests\API\Auth;

use App\Http\Requests\BaseRequest;

class LoginUserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255|exists:users,email',
            'phone' => 'required|string|max:20|exists:users,phone',
        ];
    }
}
