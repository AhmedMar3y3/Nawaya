<?php

namespace App\Http\Resources\Admin\Subscription;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => (int) $this->id,
            'workshop_id'       => (int) $this->workshop_id,
            'title'             => $this->title,
            'price'             => (float) $this->price,
            'is_offer'          => (bool) $this->is_offer,
            'offer_price'       => $this->offer_price ? (float) $this->offer_price : null,
            'offer_expiry_date' => $this->offer_expiry_date ? $this->offer_expiry_date->format('Y-m-d') : null,
        ];
    }
}
