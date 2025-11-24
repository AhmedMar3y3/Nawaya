<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'image'       => $this->image ?? env('APP_URL') . '/defaults/profile.webp',
            'name'        => $this->name,
            'email'       => $this->email,
            'gender'      => $this->gender,
            'city_id'     => $this->city_id,
            'city_name'   => $this->city->name,
            'age'         => $this->age,
            'school_id'   => $this->school_id,
            'school_name' => $this->school ? $this->school->name : null,
            'section'     => $this->section,
        ];
    }
}
