<?php

namespace App\Http\Resources\Workshop;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Enums\Workshop\WorkshopType;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id'         => $this->id,
            'title'      => $this->title,
            'teacher'    => $this->teacher,
            'type_label' => $this->type->getLocalizedName(),
        ];

        // Fields for ONLINE, ONSITE, and ONLINE_ONSITE types
        if (in_array($this->type, [WorkshopType::ONLINE, WorkshopType::ONSITE, WorkshopType::ONLINE_ONSITE])) {
            if ($this->start_date) {
                $start = $this->start_date->locale('ar')->translatedFormat('j F Y');
                if ($this->end_date) {
                    $end                = $this->end_date->locale('ar')->translatedFormat('j F Y');
                    $data['date_range'] = "{$start} الى {$end}";
                } else {
                    $data['date_range'] = $start;
                }
            }
            if ($this->start_time) {
                $startTime          = Carbon::parse($this->start_time);
                $data['start_time'] = $startTime->format('g:i a');
            }
            if ($this->end_time) {
                $endTime          = Carbon::parse($this->end_time);
                $data['end_time'] = $endTime->format('g:i a');
            }
        }

        // Fields for ONSITE and ONLINE_ONSITE types
        if (in_array($this->type, [WorkshopType::ONSITE, WorkshopType::ONLINE_ONSITE])) {
            $addressParts = [];
            if ($this->hall) {
                $addressParts[] = $this->hall;
            }
            if ($this->hotel) {
                $addressParts[] = $this->hotel;
            }
            if ($this->city) {
                $addressParts[] = $this->city;
            }
            if ($this->country && $this->country->name) {
                $addressParts[] = $this->country->name;
            }
            if (! empty($addressParts)) {
                $data['address'] = implode(' , ', $addressParts);
            }
        }

        $packagesCount = $this->relationLoaded('packages')
            ? $this->packages->count()
            : $this->packages()->count();
        $data['has_multiple_packages'] = $packagesCount > 1;

        return $data;
    }
}
