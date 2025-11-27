<?php

namespace App\Http\Requests\Admin\Product;

use App\Http\Requests\BaseRequest;

class ProductFilterRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'search'     => ['nullable', 'string', 'max:255'],
            'owner_type' => ['nullable', 'string', 'in:platform,user'],
        ];
    }
}

