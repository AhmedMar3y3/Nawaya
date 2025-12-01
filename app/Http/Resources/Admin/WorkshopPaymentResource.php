<?php

namespace App\Http\Resources\Admin;

use App\Helpers\FormatArabicDates;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'date' => $this->date->format('Y-m-d'),
            'date_formatted' => FormatArabicDates::formatArabicDate($this->date),
            'notes' => $this->notes ?? '',
        ];
    }
}

