<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Bitra Services Configuration
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'Bitra Tjänster'),

    /*
    |--------------------------------------------------------------------------
    | Default ROT Avdrag Percentage
    |--------------------------------------------------------------------------
    */
    'default_rot_percent' => 30.00,

    /*
    |--------------------------------------------------------------------------
    | Default City Multiplier
    |--------------------------------------------------------------------------
    */
    'default_city_multiplier' => 1.00,

    /*
    |--------------------------------------------------------------------------
    | Booking Settings
    |--------------------------------------------------------------------------
    */
    'booking' => [
        'number_prefix' => 'BK',
        'auto_assign' => false,
        'require_admin_approval' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Review Settings
    |--------------------------------------------------------------------------
    */
    'reviews' => [
        'require_approval' => true,
        'min_rating' => 1,
        'max_rating' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Form Settings
    |--------------------------------------------------------------------------
    */
    'forms' => [
        'max_fields' => 50,
        'max_file_size' => 10240, // KB
        'allowed_file_types' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Subscription Frequencies
    |--------------------------------------------------------------------------
    */
    'subscription_frequencies' => [
        'daily' => 'Dagligen',
        'weekly' => 'Veckovis',
        'biweekly' => 'Varannan vecka',
        'monthly' => 'Månadsvis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Booking Statuses
    |--------------------------------------------------------------------------
    */
    'booking_statuses' => [
        'pending' => 'Väntande',
        'assigned' => 'Tilldelad',
        'in_progress' => 'Pågående',
        'completed' => 'Slutförd',
        'cancelled' => 'Avbruten',
        'rejected' => 'Avvisad',
    ],

    /*
    |--------------------------------------------------------------------------
    | Field Types
    |--------------------------------------------------------------------------
    */
    'field_types' => [
        'text' => 'Textfält',
        'email' => 'E-post',
        'phone' => 'Telefon',
        'textarea' => 'Textområde',
        'number' => 'Nummer',
        'select' => 'Rullgardinsmeny',
        'radio' => 'Radioknappar',
        'checkbox' => 'Kryssrutor',
        'date' => 'Datum',
        'time' => 'Tid',
        'file' => 'Fil',
        'url' => 'URL',
        'slider' => 'Skjutreglage',
        'divider' => 'Avdelare',
        'container' => 'Container',
    ],
];

