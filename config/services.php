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

    // Google tools. Each is optional and only rendered when set; set an env var to empty to turn one off.
    'google' => [
        'site_verification' => env('GOOGLE_SITE_VERIFICATION'), // Search Console HTML-tag token
        'gtm' => env('GOOGLE_TAG_MANAGER_ID', 'GTM-MNK2J42H'), // GTM container ID (public)
        'ga4' => env('GOOGLE_ANALYTICS_ID', 'G-CVD68RBHX8'),    // GA4 measurement ID (public, appears in page source)
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
