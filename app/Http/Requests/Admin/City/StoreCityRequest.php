<?php

namespace App\Http\Requests\Admin\City;

use App\Http\Requests\BaseRequest;

class StoreCityRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:cities,name',
        ];
    }
}
