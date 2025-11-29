<?php

return [

    'ses'     => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'foloosi' => [
        'api_key'    => env('FOLOOSI_API_KEY'),
        'secret_key' => env('FOLOOSI_SECRET_KEY'),
        'base_url'   => env('FOLOOSI_BASE_URL', 'https://api.foloosi.com'),
        'callback_url' => env('APP_URL') . '/api/foloosi/callback',
    ],

];
