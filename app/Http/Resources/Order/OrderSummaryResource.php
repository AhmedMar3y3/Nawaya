<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'products' => $this->resource['products'] ?? [],
            'prices' => [
                'products_price' => (float) ($this->resource['prices']['products_price'] ?? 0),
                'tax' => (float) ($this->resource['prices']['tax'] ?? 0),
                'total_price' => (float) ($this->resource['prices']['total_price'] ?? 0),
            ],
            'payment_options' => [
                'online_payment' => (bool) ($this->resource['payment_options']['online_payment'] ?? true),
                'bank_transfer' => (bool) ($this->resource['payment_options']['bank_transfer'] ?? true),
            ],
        ];

        if ($this->resource['bank_account'] !== null) {
            $data['bank_account'] = $this->resource['bank_account'];
        }

        return $data;
    }
}

