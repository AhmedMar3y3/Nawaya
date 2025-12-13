<?php

namespace App\Http\Resources\Admin\Charity;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CharityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $pricePerSeat = $this->price / $this->number_of_seats;
        $usedSeatsPrice = $pricePerSeat * ($this->used_seats ?? 0);
        $availableAmount = $this->price - $usedSeatsPrice;

        return [
            'id'                => $this->id,
            'user_id'           => $this->user_id,
            'user_name'         => $this->user ? $this->user->full_name : '-',
            'user_phone'        => $this->user ? $this->user->phone : '-',
            'workshop_id'       => $this->workshop_id,
            'workshop_title'    => $this->workshop ? $this->workshop->title : '-',
            'package_id'        => $this->package_id,
            'package_title'     => $this->package ? $this->package->title : '-',
            'number_of_seats'   => $this->number_of_seats,
            'used_seats'        => $this->used_seats ?? 0,
            'available_seats'   => $this->number_of_seats - ($this->used_seats ?? 0),
            'price'             => $this->price,
            'price_per_seat'    => $pricePerSeat,
            'used_amount'       => $usedSeatsPrice,
            'available_amount'  => $availableAmount,
            'status'            => $this->status->value,
            'payment_type'      => $this->payment_type ? $this->payment_type->value : null,
            'created_at'        => $this->created_at ? $this->created_at->format('Y-m-d') : null,
            'created_at_ar'     => $this->created_at ? \App\Helpers\FormatArabicDates::formatArabicDate($this->created_at) : '-',
        ];
    }
}

