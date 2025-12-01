<?php

namespace App\Http\Resources\Subscription;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'subscription_id' => $this->resource['subscription_id'],
            'subscription_details' => $this->resource['subscription_details'] ?? [],
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



