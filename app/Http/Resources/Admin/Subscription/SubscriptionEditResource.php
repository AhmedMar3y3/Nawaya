<?php

namespace App\Http\Resources\Admin\Subscription;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionEditResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'workshop_id'     => $this->workshop_id,
            'package_id'      => $this->package_id,
            'paid_amount'     => $this->paid_amount,
            'payment_type'    => $this->payment_type?->value,
            'transferer_name' => $this->transferer_name,
            'notes'           => $this->notes,
            'workshop_title'  => $this->workshop?->title ?? '',
            'user_name'       => $this->user?->full_name ?? ($this->full_name ?? ''),
            'user_balance'    => $this->user?->balance ?? 0,
        ];
    }
}
