<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Teleconsultation Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for video conferencing and teleconsultation features
    |
    */

    'provider' => env('TELECONSULTATION_PROVIDER', 'agora'),

    'providers' => [
        'agora' => [
            'app_id' => env('AGORA_APP_ID'),
            'app_certificate' => env('AGORA_APP_CERTIFICATE'),
        ],
        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'api_key' => env('TWILIO_API_KEY'),
            'api_secret' => env('TWILIO_API_SECRET'),
        ],
        'jitsi' => [
            'domain' => env('JITSI_DOMAIN', 'meet.jit.si'),
        ],
    ],

    'session' => [
        'max_duration' => env('TELECONSULTATION_MAX_DURATION', 120), // minutes
        'recording_enabled' => env('TELECONSULTATION_RECORDING', false),
        'auto_end_on_exit' => env('TELECONSULTATION_AUTO_END', true),
    ],

    'features' => [
        'chat' => true,
        'screen_share' => true,
        'recording' => env('TELECONSULTATION_RECORDING', false),
        'waiting_room' => true,
    ],

];
