<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FERRO Brand Configuration
    |--------------------------------------------------------------------------
    */

    'brand_name'   => 'FERRO',
    'admin_email'  => env('FERRO_ADMIN_EMAIL', 'admin@ferro.com'),
    'locales'      => ['en', 'ar'],
    'default_locale' => 'en',

    /*
    | Inventory thresholds
    */
    'low_stock_threshold' => (int) env('FERRO_LOW_STOCK_THRESHOLD', 10),

    /*
    | Loyalty programme
    */
    'loyalty' => [
        'points_per_currency_unit' => 1,   // 1 point per 1 SAR/USD spent
        'welcome_bonus'            => 100,
    ],

    /*
    | Abandoned cart recovery
    */
    'abandoned_cart' => [
        'delay_minutes'  => 60,
        'max_emails'     => 3,
        'gap_hours'      => 24,
    ],
];
