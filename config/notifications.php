<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notifications Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for notifications and alerts
    |
    */

    'channels' => [
        'mail' => env('NOTIFICATION_MAIL_ENABLED', true),
        'sms' => env('NOTIFICATION_SMS_ENABLED', false),
        'push' => env('NOTIFICATION_PUSH_ENABLED', false),
        'database' => true, // Always enabled for in-app notifications
    ],

    'sms' => [
        'provider' => env('SMS_PROVIDER', 'twilio'),
        'from' => env('SMS_FROM'),
        'providers' => [
            'twilio' => [
                'sid' => env('TWILIO_ACCOUNT_SID'),
                'token' => env('TWILIO_AUTH_TOKEN'),
                'from' => env('TWILIO_FROM'),
            ],
        ],
    ],

    'appointment_reminders' => [
        'enabled' => env('APPOINTMENT_REMINDERS_ENABLED', true),
        'hours_before' => env('APPOINTMENT_REMINDERS_HOURS', 24),
        'channels' => ['mail', 'sms'], // Which channels to use for reminders
    ],

    'marketing' => [
        'enabled' => env('MARKETING_EMAILS_ENABLED', false),
    ],

];
