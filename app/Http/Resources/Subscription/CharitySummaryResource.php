<?php

namespace App\Http\Resources\Subscription;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CharitySummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'payment_options' => [
                'online_payment' => (bool) ($this->resource['payment_options']['online_payment'] ?? true),
                'bank_transfer' => (bool) ($this->resource['payment_options']['bank_transfer'] ?? true),
            ],
        ];

        if (isset($this->resource['charity_id'])) {
            $data['charity_id'] = $this->resource['charity_id'];
        }

        if (isset($this->resource['charity_details'])) {
            $data['charity_details'] = $this->resource['charity_details'];
        }

        if ($this->resource['bank_account'] !== null) {
            $data['bank_account'] = $this->resource['bank_account'];
        }

        if (isset($this->resource['invoice_url'])) {
            $data['invoice_url'] = $this->resource['invoice_url'];
        }

        if (isset($this->resource['invoice_id'])) {
            $data['invoice_id'] = $this->resource['invoice_id'];
        }

        if (isset($this->resource['message'])) {
            $data['message'] = $this->resource['message'];
        }

        return $data;
    }
}
