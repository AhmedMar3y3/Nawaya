<?php

namespace App\Http\Resources\Admin\Subscription;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DebtResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $this->user;
        $debt = $this->price - $this->paid_amount;

        return [
            'id'             => (int) $this->id,
            'user_name'      => $user ? $user->full_name : ($this->full_name ?? '-'),
            'user_phone'     => $user ? $user->phone : ($this->phone ?? '-'),
            'workshop_title' => $this->workshop ? $this->workshop->title : '-',
            'debt'           => (float) $debt,
            'price'          => (float) $this->price,
            'paid_amount'    => (float) $this->paid_amount,
        ];
    }
}
