<?php

namespace App\Http\Resources\DrHope;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'price' => $this->price,
        ];
    }
}
