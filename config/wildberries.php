<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Wildberries API Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Wildberries API settings. You can get your
    | API token from your Wildberries seller personal cabinet.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | API Token
    |--------------------------------------------------------------------------
    |
    | Your Wildberries API token. You can create it in your seller cabinet
    | in the API settings section.
    |
    */
    'token' => env('WILDBERRIES_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Sandbox Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, the client will use sandbox URLs for testing.
    |
    */
    'sandbox' => env('WILDBERRIES_SANDBOX', false),

    /*
    |--------------------------------------------------------------------------
    | API Base URLs
    |--------------------------------------------------------------------------
    |
    | The base URLs for different Wildberries API services.
    |
    */
    'base_urls' => [
        'production' => [
            'common' => 'https://common-api.wildberries.ru',
            'content' => 'https://content-api.wildberries.ru',
            'marketplace' => 'https://marketplace-api.wildberries.ru',
            'statistics' => 'https://statistics-api.wildberries.ru',
            'advert' => 'https://advert-api.wildberries.ru',
            'feedbacks' => 'https://feedbacks-api.wildberries.ru',
            'chat' => 'https://buyer-chat-api.wildberries.ru',
            'supplies' => 'https://supplies-api.wildberries.ru',
            'returns' => 'https://returns-api.wildberries.ru',
            'documents' => 'https://documents-api.wildberries.ru',
            'finance' => 'https://finance-api.wildberries.ru',
        ],
        'sandbox' => [
            'common' => 'https://common-api-sandbox.wildberries.ru',
            'content' => 'https://content-api-sandbox.wildberries.ru',
            'marketplace' => 'https://marketplace-api-sandbox.wildberries.ru',
            'statistics' => 'https://statistics-api-sandbox.wildberries.ru',
            'advert' => 'https://advert-api-sandbox.wildberries.ru',
            'feedbacks' => 'https://feedbacks-api-sandbox.wildberries.ru',
            'chat' => 'https://buyer-chat-api-sandbox.wildberries.ru',
            'supplies' => 'https://supplies-api-sandbox.wildberries.ru',
            'returns' => 'https://returns-api-sandbox.wildberries.ru',
            'documents' => 'https://documents-api-sandbox.wildberries.ru',
            'finance' => 'https://finance-api-sandbox.wildberries.ru',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the HTTP client used to make API requests.
    |
    */
    'http' => [
        'timeout' => 30,
        'retry_attempts' => 3,
        'retry_delay' => 1000, // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configuration for handling API rate limits.
    |
    */
    'rate_limiting' => [
        'enabled' => true,
        'default_limit' => 60, // requests per minute
        'burst_limit' => 10,   // burst requests
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Configure logging for API requests and responses.
    |
    */
    'logging' => [
        'enabled' => env('WILDBERRIES_LOGGING', false),
        'channel' => env('WILDBERRIES_LOG_CHANNEL', 'default'),
        'level' => env('WILDBERRIES_LOG_LEVEL', 'info'),
    ],
];
