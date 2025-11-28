<?php

namespace App\Http\Requests\API\Cart;

use App\Http\Requests\BaseRequest;

class AddToCartRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }
}

