<?php

namespace App\Http\Requests\API\Profile;

use App\Http\Requests\BaseRequest;

class StoreSupportMessageRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'title'   => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ];
    }
}
