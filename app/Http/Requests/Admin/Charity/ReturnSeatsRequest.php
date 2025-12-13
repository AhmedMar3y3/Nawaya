<?php

namespace App\Http\Requests\Admin\Charity;

use App\Http\Requests\BaseRequest;

class ReturnSeatsRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'seats_count' => ['required', 'integer', 'min:1'],
            'action'      => ['required', 'string', 'in:keep_balance,refund'],
        ];
    }
}

