<?php

namespace App\Http\Requests\API\Cart;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateCartRequest extends BaseRequest
{
    public function rules(): array
    {
        $user = auth()->user();
        $cartId = $user->cart?->id;

        return [
            'items' => [
                'required',
                'array',
            ],
            'items.*.cart_item_id' => [
                'required',
                'integer',
                Rule::exists('cart_items', 'id')->where('cart_id', $cartId),
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }
}

