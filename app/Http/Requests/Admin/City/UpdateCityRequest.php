<?php

namespace App\Http\Requests\Admin\City;

use App\Http\Requests\BaseRequest;

class UpdateCityRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
        ];
    }
}
