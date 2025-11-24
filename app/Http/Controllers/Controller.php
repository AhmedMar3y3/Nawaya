<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, HttpResponses;

    public function countries()
    {
        $countries = \App\Models\Country::get(['id', 'name','code']);
        return $this->successWithDataResponse($countries);
    }
}
