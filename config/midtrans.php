<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    'sandbox_base_url' => env('MIDTRANS_SANDBOX_BASE_URL', 'https://api.sandbox.midtrans.com'),
    'production_base_url' => env('MIDTRANS_PRODUCTION_BASE_URL', 'https://api.midtrans.com'),

    'base_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? env('MIDTRANS_PRODUCTION_BASE_URL', 'https://api.midtrans.com')
        : env('MIDTRANS_SANDBOX_BASE_URL', 'https://api.sandbox.midtrans.com'),
];