<?php

namespace App\Http\Resources\Admin\Subscription;

use Illuminate\Http\Request;
use App\Helpers\FormatArabicDates;
use Illuminate\Http\Resources\Json\JsonResource;

class BalanceSubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $this->user;

        return [
            'id'             => (int) $this->id,
            'user_name'      => $user ? $user->full_name : ($this->full_name ?? '-'),
            'user_phone'     => $user ? $user->phone : ($this->phone ?? '-'),
            'workshop_title' => $this->workshop ? $this->workshop->title : '-',
            'paid_amount'    => (float) $this->paid_amount,
            'created_at'     => $this->created_at ? FormatArabicDates::formatArabicDate($this->created_at) : '-',
        ];
    }
}
