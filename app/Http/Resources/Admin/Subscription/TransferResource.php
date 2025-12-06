<?php

namespace App\Http\Resources\Admin\Subscription;

use Illuminate\Http\Request;
use App\Helpers\FormatArabicDates;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $subscription = $this->subscription;
        $user         = $subscription->user;

        return [
            'id'            => (int) $this->id,
            'user_name'     => $user ? $user->full_name : ($subscription->full_name ?? '-'),
            'user_phone'    => $user ? $user->phone : ($subscription->phone ?? '-'),
            'from_workshop' => $this->workshopFrom ? $this->workshopFrom->title : '-',
            'to_workshop'   => $this->workshopTo ? $this->workshopTo->title : '-',
            'old_price'     => (float) $this->old_price,
            'new_price'     => (float) $this->new_price,
            'paid_amount'   => (float) $this->paid_amount,
            'created_at'    => FormatArabicDates::formatArabicDate($this->created_at),
        ];
    }
}
