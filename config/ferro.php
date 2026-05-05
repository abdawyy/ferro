<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FERRO Brand Configuration
    |--------------------------------------------------------------------------
    */

    'brand_name' => 'FERRO',
    'admin_email' => env('FERRO_ADMIN_EMAIL', 'admin@ferro.com'),

    /*
    | Order / transactional mail
    | queue: false = send synchronously in the request (no worker needed).
    | queue: true  = Mail::queue() (requires QUEUE_CONNECTION worker).
    */
    'mail' => [
        'queue' => (bool) env('FERRO_MAIL_QUEUE', false),
    ],
    'locales' => ['en', 'ar'],
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
        'welcome_bonus' => 100,
    ],

    /*
    | Abandoned cart recovery
    */
    'abandoned_cart' => [
        'delay_minutes' => 60,
        'max_emails' => 3,
        'gap_hours' => 24,
    ],

    /*
    |--------------------------------------------------------------------------
    | Page backgrounds (storefront)
    |--------------------------------------------------------------------------
    | Brand imagery lives in public/images/brand. Fixed backdrop sits under
    | content (see partials/page-backdrop); heroes reference the same keys.
    */
    'page_backgrounds' => [
        'heroes' => [
            'home' => 'images/brand/ferro-hex-logo.png',
            'about' => 'images/brand/ferro-brand-marble.png',
            'contact' => 'images/brand/ferro-texture-dark.png',
            'quiz' => 'images/brand/ferro-texture-dark.png',
            'shop' => 'images/brand/ferro-hex-logo.png',
            'brand_story' => 'images/brand/ferro-brand-marble.png',
            'about_story' => 'images/brand/ferro-hex-logo.png',
        ],
        'backdrop_position' => [
            '__default' => 'center',
            'home' => 'right center',
            'products.index' => 'right center',
            'login' => 'right center',
            'register' => 'right center',
            'password.request' => 'right center',
            'password.reset' => 'right center',
        ],
        'backdrop_routes' => [
            '__default' => 'images/brand/ferro-texture-dark.png',
            'home' => 'images/brand/ferro-texture-dark.png',
            'about' => 'images/brand/ferro-brand-marble.png',
            'contact' => 'images/brand/ferro-brand-marble.png',
            'quiz' => 'images/brand/ferro-texture-dark.png',
            'products.index' => 'images/brand/ferro-hex-logo.png',
            'products.show' => 'images/brand/ferro-texture-dark.png',
            'cart' => 'images/brand/ferro-brand-marble.png',
            'checkout' => 'images/brand/ferro-brand-marble.png',
            'account' => 'images/brand/ferro-texture-dark.png',
            'orders.show' => 'images/brand/ferro-texture-dark.png',
            'invoices.download' => 'images/brand/ferro-texture-dark.png',
            'login' => 'images/brand/ferro-hex-logo.png',
            'register' => 'images/brand/ferro-hex-logo.png',
            'password.request' => 'images/brand/ferro-hex-logo.png',
            'password.reset' => 'images/brand/ferro-hex-logo.png',
        ],
    ],
];
