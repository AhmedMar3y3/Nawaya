<?php

namespace App\Http\Resources\Admin;

use App\Helpers\FormatArabicDates;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            'title' => $this->title,
            'workshop_id' => $this->workshop_id,
            'workshop_title' => $this->workshop ? $this->workshop->title : null,
            'invoice_number' => $this->invoice_number,
            'image' => $this->image,
            'vendor' => $this->vendor,
            'amount' => $this->amount,
            'notes' => $this->notes,
            'is_including_tax' => $this->is_including_tax,
            'date' => $this->created_at->format('Y-m-d'),
            'date_formatted' => FormatArabicDates::formatArabicDate($this->created_at),
        ];
    }
}

