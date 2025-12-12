<?php

namespace App\Http\Controllers\User;

use App\Models\Setting;
use App\Models\Workshop;
use App\Traits\HttpResponses;
use App\Enums\Workshop\WorkshopType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Home\HomeResource;
use App\Http\Resources\Home\SettingsResource;

class HomeController extends Controller
{
    use HttpResponses;

    public function earliestOnlineWorkshop()
    {
        $workshop = Workshop::whereIn('type', [WorkshopType::ONLINE->value, WorkshopType::ONLINE_ONSITE->value])->whereNotNull('online_link')->oldest('start_date')->oldest('start_time')->first();
        return $this->successWithDataResponse(HomeResource::make($workshop));
    }

    public function settings()
    {
        return $this->successWithDataResponse(new SettingsResource(Setting::pluck('value', 'key')));
    }
}
