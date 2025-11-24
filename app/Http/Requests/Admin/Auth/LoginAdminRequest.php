<?php

namespace App\Http\Requests\Admin\Auth;

use App\Http\Requests\BaseRequest;

class LoginAdminRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|string',
        ];
    }
}
