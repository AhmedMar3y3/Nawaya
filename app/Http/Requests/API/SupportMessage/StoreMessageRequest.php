<?php

namespace App\Http\Requests\API\SupportMessage;

use App\Http\Requests\BaseRequest;

class StoreMessageRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'message' => 'required|string|max:2000',
        ];
    }
}
