<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'items' => CartItemResource::collection($this->resource['items'] ?? []),
            'prices' => [
                'products_price' => (float) ($this->resource['prices']['products_price'] ?? 0),
                'tax' => (float) ($this->resource['prices']['tax'] ?? 0),
                'total_price' => (float) ($this->resource['prices']['total_price'] ?? 0),
            ],
        ];
    }
}

