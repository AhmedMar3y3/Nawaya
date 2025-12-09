<?php

namespace App\Http\Resources\DrHope;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'workshop_title'     => $this->workshop->title,
            'workshop_teacher'   => $this->workshop->teacher,
            'rating'             => $this->rating,
            'review'             => $this->review ?? null,
            'user_name_and_date' => $this->user->full_name . ' ' . $this->created_at->format('j/n/Y'),
        ];
    }
}
