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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'kakao' => [
        'client_id' => env('APP_ENV') === 'production' 
            ? env('KAKAO_CLIENT_ID', 'f0298b393c792dbd2b88f93af6ede529') 
            : null,
        'client_secret' => env('APP_ENV') === 'production' 
            ? env('KAKAO_CLIENT_SECRET', '9PQPYwDSa5Et5mpFjdiiTEcDzRGbGPIo') 
            : null,
        'redirect' => env('APP_ENV') === 'production'
            ? 'https://gonggoo.kro.kr/auth/kakao/callback'
            : null,
        'guzzle' => [
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => env('APP_ENV') === 'production',
                CURLOPT_SSL_VERIFYHOST => env('APP_ENV') === 'production' ? 2 : 0,
            ],
            'verify' => env('APP_ENV') === 'production',
        ],
    ],


];
