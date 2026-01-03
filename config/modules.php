<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Configuration
    |--------------------------------------------------------------------------
    |
    | Configure which modules are enabled for your Clinora installation.
    | Modules can be enabled/disabled based on your subscription or needs.
    |
    */

    'core' => [
        'enabled' => true,
    ],

    'psychology' => [
        'enabled' => env('MODULE_PSYCHOLOGY_ENABLED', true),
        'assessments' => [
            'bdi2' => true,
            'phq9' => true,
            'gad7' => true,
        ],
    ],

    'physiotherapy' => [
        'enabled' => env('MODULE_PHYSIOTHERAPY_ENABLED', false),
    ],

    'nutrition' => [
        'enabled' => env('MODULE_NUTRITION_ENABLED', false),
    ],

    'patient_portal' => [
        'enabled' => env('PATIENT_PORTAL_ENABLED', true),
        'self_booking' => env('PATIENT_PORTAL_SELF_BOOKING', true),
        'online_payments' => env('PATIENT_PORTAL_PAYMENTS', true),
    ],

    'teleconsultation' => [
        'enabled' => env('TELECONSULTATION_ENABLED', true),
        'provider' => env('TELECONSULTATION_PROVIDER', 'agora'), // agora, twilio, jitsi
        'recording' => env('TELECONSULTATION_RECORDING', false),
    ],

];
