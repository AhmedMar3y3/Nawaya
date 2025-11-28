<?php

namespace App\Http\Resources\Workshop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopPackageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => (float) $this->price,
            'is_offer' => $this->is_offer,
            'offer_price' => $this->when($this->is_offer, fn() => (float) $this->offer_price),
            'offer_expiry_date' => $this->when($this->is_offer && $this->offer_expiry_date, fn() => $this->offer_expiry_date->format('Y-m-d')),
            'features' => $this->whenNotNull($this->features),
            
        ];
    }
}

