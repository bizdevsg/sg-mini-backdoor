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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'mini_micro' => [
        'base_url' => env('MINI_MICRO_BASE_URL', 'https://mmapiuat.sgberjangka.id'),
        'api_key' => env('MINI_MICRO_API_KEY'),
        'dry_run' => (bool) env('MINI_MICRO_DRY_RUN', true),
        'broadcast_cooldown_minutes' => (int) env('MINI_MICRO_BROADCAST_COOLDOWN_MINUTES', 5),
        'timeout' => (int) env('MINI_MICRO_TIMEOUT', 15),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
