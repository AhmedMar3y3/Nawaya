<?php

namespace App\Http\Resources\Admin;

use App\Helpers\FormatArabicDates;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'user'           => [
                'id'        => $this->user->id,
                'full_name' => $this->user->full_name,
                'email'     => $this->user->email,
                'phone'     => $this->user->phone,
            ],
            'workshop'       => [
                'id'      => $this->workshop->id,
                'title'   => $this->workshop->title,
                'teacher' => $this->workshop->teacher,
                'type'    => $this->workshop->type->getLocalizedName(),
            ],
            'rating'         => $this->rating,
            'review'         => $this->review ?? '-',
            'is_active'      => $this->is_active,
            'created_at'     =>  FormatArabicDates::formatArabicDate($this->created_at),
        ];
    }
}
