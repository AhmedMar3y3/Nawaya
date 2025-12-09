<?php

return [
    'secret_key' => env('TAP_SECRET_KEY'),
    'public_key' => env('TAP_PUBLIC_KEY'),
    'url' => rtrim(env('TAP_API_URL', 'https://api.tap.company'), '/'),
    'merchant_id' => env('TAP_MERCHANT_ID'),
];
