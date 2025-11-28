<?php

namespace App\Http\Controllers\User;

use App\Models\Setting;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\Home\SettingsResource;

class HomeController extends Controller
{
    use HttpResponses;

    public function settings()
    {
        return $this->successWithDataResponse(new SettingsResource(Setting::pluck('value', 'key')));
    }
}
