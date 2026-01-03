<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Billing Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for invoice generation and payment processing
    |
    */

    'default_currency' => env('BILLING_CURRENCY', 'EUR'),

    'tax' => [
        'enabled' => env('BILLING_TAX_ENABLED', true),
        'rate' => env('BILLING_TAX_RATE', 21), // IVA en España
        'name' => env('BILLING_TAX_NAME', 'IVA'),
    ],

    'invoice' => [
        'prefix' => env('INVOICE_PREFIX', 'INV'),
        'date_format' => 'd/m/Y',
        'due_days' => env('INVOICE_DUE_DAYS', 30),
        'storage_disk' => env('INVOICE_STORAGE_DISK', 'local'),
        'storage_path' => 'invoices',
    ],

    'payment_methods' => [
        'cash' => true,
        'bank_transfer' => true,
        'card' => env('BILLING_CARD_ENABLED', true),
    ],

    'payment_gateways' => [
        'stripe' => [
            'enabled' => env('STRIPE_ENABLED', false),
            'public_key' => env('STRIPE_PUBLIC_KEY'),
            'secret_key' => env('STRIPE_SECRET_KEY'),
        ],
        'paypal' => [
            'enabled' => env('PAYPAL_ENABLED', false),
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'secret' => env('PAYPAL_SECRET'),
            'mode' => env('PAYPAL_MODE', 'sandbox'), // sandbox or live
        ],
    ],

];
