<?php

namespace App\Http\Resources\Workshop;

use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Enums\Workshop\WorkshopType;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $settings = Setting::pluck('value', 'key');

        $dateRange = null;
        if (in_array($this->type, [WorkshopType::ONLINE, WorkshopType::ONSITE, WorkshopType::ONLINE_ONSITE])) {
            if ($this->start_date) {
            $start = $this->start_date->locale('ar')->translatedFormat('j F Y');
            if ($this->end_date) {
                $end = $this->end_date->locale('ar')->translatedFormat('j F Y');
                $dateRange = "{$start} الى {$end}";
            } else {
                $dateRange = $start;
            }
            }
        }

        $data = [
            'id'                        => $this->id,
            ...($dateRange ? ['date_range' => $dateRange] : []),
            'title'                     => $this->title,
            'description'               => $this->description,
            'subject_of_discussion'     => $this->subject_of_discussion,
            'packages'                  => WorkshopPackageResource::collection($this->packages),
            // 'workshop_policy' => $settings->get('workshop_policy'),
            'workshop_returning_policy' => $settings->get('workshop_returning_policy'),
        ];

        return $data;
    }
}
