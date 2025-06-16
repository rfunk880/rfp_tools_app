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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

     /**
     * Google OAuth
     * https://console.developers.google.com
     */
    'google' => [
        'client_id' => env('GOOGLE_OAUTH_CLIENT_ID'),
        'client_secret' => env('GOOGLE_OAUTH_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_OAUTH_REDIRECT'),
    ],

    /**
     * Github OAuth
     * https://github.com/settings/applications/new
     */
    'github' => [
        'client_id' => env('GITHUB_OAUTH_CLIENT_ID'),
        'client_secret' => env('GITHUB_OAUTH_CLIENT_SECRET'),
        'redirect' => env('GITHUB_OAUTH_REDIRECT'),
    ],

    'youtube' => [
        'api_key' => env("YOUTUBE_API_KEY")
    ],
    'microsoft' => [
        'object_id' => 'c7da5be4-5d13-4cbc-84c7-bf456289492b',
        'tenant_id' => 'f90f90f8-a227-4c09-a239-0e4c2c118231',
        'client_id' => '2349602b-c193-4541-be3f-db1f03488415',
        'client_secret' => 'KIZ8Q~c_nlnGJ3HDsM3VcnBocARtkUx4ORTDJcPw',
        // 'value' => 'hQt8QNC3PaPMTQudHXRZfHTrohJKuAUXL48aIS',
        'scope' => 'https://graph.microsoft.com/.default'
    ],
    'google_calendar' => [
        'subject' => 'calendarservice@calendar-project-432304.iam.gserviceaccount.com',
        'scope' => 'https://www.googleapis.com/auth/calendar',
        'email' => 'calendarservice@calendar-project-432304.iam.gserviceaccount.com',
        'calendar_id' => '0a74f38fc58c0d76a29b6b206744c5c4e025053154cd104011810364b9124cf3@group.calendar.google.com'
    ]

];
