<?php

namespace App\Http\Requests\Admin\Charity;

use App\Http\Requests\BaseRequest;

class AssignCharitySeatRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'user_id'       => ['required', 'integer', 'exists:users,id'],
            'charity_notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}

