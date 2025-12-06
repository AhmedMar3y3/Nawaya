<?php

namespace App\Http\Resources\Admin\Subscription;

use Illuminate\Http\Request;
use App\Helpers\FormatArabicDates;
use Illuminate\Http\Resources\Json\JsonResource;

class RefundResource extends JsonResource
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
            'refund_type'    => $this->refund_type ? __('enums.refund_types.' . $this->refund_type->value, [], 'ar') : '-',
            'refund_notes'   => $this->refund_notes ?? '-',
            'updated_at'     => FormatArabicDates::formatArabicDate($this->updated_at),
        ];
    }
}
