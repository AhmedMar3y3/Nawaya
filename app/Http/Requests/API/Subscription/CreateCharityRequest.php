<?php

namespace App\Http\Requests\API\Subscription;

use App\Http\Requests\BaseRequest;

class CreateCharityRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'package_id'        => ['required', 'integer', 'exists:workshop_packages,id'],
            'number_of_seats'   => ['required', 'integer', 'min:1'],
        ];
    }
}
