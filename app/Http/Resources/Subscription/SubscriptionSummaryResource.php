<?php

namespace App\Http\Resources\Subscription;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'payment_options' => [
                'online_payment' => (bool) ($this->resource['payment_options']['online_payment'] ?? true),
                'bank_transfer' => (bool) ($this->resource['payment_options']['bank_transfer'] ?? true),
            ],
        ];

        if (isset($this->resource['subscriptions']) && is_array($this->resource['subscriptions'])) {
            $data['subscriptions'] = $this->resource['subscriptions'];
        }

        if ($this->resource['bank_account'] !== null) {
            $data['bank_account'] = $this->resource['bank_account'];
        }

        return $data;
    }
}



