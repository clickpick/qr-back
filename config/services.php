<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // socialite
    'vkontakte' => [
        'client_id' => env('VKONTAKTE_ADMIN_KEY'),
        'client_secret' => env('VKONTAKTE_ADMIN_SECRET'),
        'redirect' => env('VKONTAKTE_REDIRECT_URI')
    ],


    'vk' => [
        'app' => [
            'id' => env('VK_APP_ID'),
            'secret' => env('VK_APP_SECRET'),
            'service' => env('VK_APP_SERVICE_KEY'),
        ],
        'pay' => [
            'merchant_id' => env('VK_MERCHANT_ID'),
            'secret' => env('VK_MERCHANT_SECRET')
        ]
    ],

    'yandex' => [
        'maps' => [
            'key' => env('YANDEX_MAPS_API_KEY')
        ]
    ]

];
