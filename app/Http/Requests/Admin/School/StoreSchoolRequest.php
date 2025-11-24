<?php

namespace App\Http\Requests\Admin\School;

use App\Http\Requests\BaseRequest;

class StoreSchoolRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
        ];
    }
}

