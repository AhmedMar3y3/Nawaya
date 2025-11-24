<?php

namespace App\Http\Requests\API\Auth;

use App\Http\Requests\BaseRequest;

class VerifyFirebaseTokenRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'id_token'  => ['required', 'string'],
        ];
    }
}
