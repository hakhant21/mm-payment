<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configuration
    |--------------------------------------------------------------------------
    */
    'app_id' => env('PAYMENT_APP_ID', '1234567890'),
    'merchant_code' => env('PAYMENT_MERCHANT_CODE', 'MERCHANT123'),
    'merchant_key' => env('PAYMENT_MERCHANT_KEY', 'SECRETKEY'),
    'notify_url' => env('PAYMENT_NOTIFY_URL', 'https://yourdomain.com/payment/notify'),
    
    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    */
    'endpoints' => [
        'place_order' => env('PAYMENT_PLACE_ORDER_URL', 'https://api.payment.com/gateway/placeorder'),
        'query_order' => env('PAYMENT_QUERY_ORDER_URL', 'https://api.payment.com/gateway/queryorder'),
        'refund' => env('PAYMENT_REFUND_URL', 'https://api.payment.com/gateway/refund'),
    ],

    /*
    |--------------------------------------------------------------------------
    | SSL Configuration
    |--------------------------------------------------------------------------
    */
    'ssl' => [
        'cert_path' => env('PAYMENT_SSL_CERT_PATH'),
        'key_path' => env('PAYMENT_SSL_KEY_PATH'),
        'key_password' => env('PAYMENT_SSL_KEY_PASSWORD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Options
    |--------------------------------------------------------------------------
    */
    'http' => [
        'timeout' => env('PAYMENT_HTTP_TIMEOUT', 30),
        'connect_timeout' => env('PAYMENT_HTTP_CONNECT_TIMEOUT', 10),
        'debug' => env('PAYMENT_DEBUG', false),
        'verify_ssl' => env('PAYMENT_VERIFY_SSL', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Request Values
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'currency' => env('PAYMENT_CURRENCY', 'MMK'),
        'timeout_express' => env('PAYMENT_TIMEOUT_EXPRESS', '30m'),
        'version' => env('PAYMENT_API_VERSION', '3.0'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => env('PAYMENT_LOGGING', true),
        'channel' => env('PAYMENT_LOG_CHANNEL', 'stack'), // Use null for default stack
    ],
];