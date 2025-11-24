<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAuthResource extends JsonResource
{
    private $token;
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->full_name,
            'email' => $this->email,
            'token' => $this->token,
        ];
    }
}
