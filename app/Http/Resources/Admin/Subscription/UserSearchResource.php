<?php

namespace App\Http\Resources\Admin\Subscription;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSearchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'full_name' => $this->full_name,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'balance'   => (float) ($this->balance ?? 0),
        ];
    }
}

