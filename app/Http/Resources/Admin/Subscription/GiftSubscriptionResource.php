<?php

namespace App\Http\Resources\Admin\Subscription;

use Illuminate\Http\Request;
use App\Helpers\FormatArabicDates;
use Illuminate\Http\Resources\Json\JsonResource;

class GiftSubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => (int) $this->id,
            'sender_name'      => $this->gifter ? $this->gifter->full_name : '-',
            'workshop_title'   => $this->workshop ? $this->workshop->title : '-',
            'receiver_name'    => $this->user ? $this->user->full_name : ($this->full_name ?? '-'),
            'created_at'       =>  FormatArabicDates::formatArabicDate($this->created_at),
            'user_id'          => $this->user_id ? (int) $this->user_id : null,
            'gift_user_id'     => $this->gift_user_id ? (int) $this->gift_user_id : null,
            'is_gift_approved' => (bool) $this->is_gift_approved,
        ];
    }
}
