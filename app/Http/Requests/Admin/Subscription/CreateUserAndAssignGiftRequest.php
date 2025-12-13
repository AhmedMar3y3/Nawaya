<?php

namespace App\Http\Requests\Admin\Subscription;

use App\Http\Requests\BaseRequest;

class CreateUserAndAssignGiftRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'full_name'  => ['required', 'string', 'max:255'],
            'phone'      => ['required', 'string', 'unique:users,phone', 'max:20'],
            'email'      => ['required', 'email', 'unique:users,email', 'max:255'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
        ];
    }
}
