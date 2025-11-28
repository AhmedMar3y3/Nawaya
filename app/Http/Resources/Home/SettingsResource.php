<?php
namespace App\Http\Resources\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'welcome_message' => $this->resource->get('welcome'),
            'logo'            => env('APP_URL') . '/' . $this->resource->get('logo'),
            'facebook'        => $this->resource->get('facebook'),
            'instagram'       => $this->resource->get('instgram'),
            'tiktok'          => $this->resource->get('tiktok'),
            'snapchat'        => $this->resource->get('snapchat'),
            'whatsapp'        => $this->resource->get('whatsapp'),
            'twitter'         => $this->resource->get('twitter'),
        ];
    }
}
