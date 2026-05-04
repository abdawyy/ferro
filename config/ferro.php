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

    /*
    |--------------------------------------------------------------------------
    | Page backgrounds (storefront)
    |--------------------------------------------------------------------------
    | Full-bleed hero fallbacks when files under public/images are absent.
    | Route backdrop: fixed layer under content (null = skip for that route).
    | Replace URLs with your own assets in public/images and use asset() in views.
    */
    'page_backgrounds' => [
        'heroes' => [
            'home'         => 'https://images.unsplash.com/photo-1556228578-0d385b1a4d571?auto=format&fit=crop&w=2400&q=80',
            'about'        => 'https://images.unsplash.com/photo-1612817288484-6f916006741a?auto=format&fit=crop&w=2400&q=80',
            'contact'      => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=2400&q=80',
            'quiz'         => 'https://images.unsplash.com/photo-1612817288484-6f916006741a?auto=format&fit=crop&w=2400&q=75',
            'shop'         => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=2400&q=80',
            'brand_story'  => 'https://images.unsplash.com/photo-1608248543803-6ec288bfef52?auto=format&fit=crop&w=1600&q=80',
            'about_story'  => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?auto=format&fit=crop&w=1600&q=80',
        ],
        'backdrop_routes' => [
            '__default'          => 'https://images.unsplash.com/photo-1556228578-0d385b1a4d571?auto=format&fit=crop&w=2400&q=65',
            'home'               => 'https://images.unsplash.com/photo-1556228578-0d385b1a4d571?auto=format&fit=crop&w=2400&q=60',
            'about'              => 'https://images.unsplash.com/photo-1612817288484-6f916006741a?auto=format&fit=crop&w=2400&q=60',
            'contact'            => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=2400&q=60',
            'quiz'               => 'https://images.unsplash.com/photo-1612817288484-6f916006741a?auto=format&fit=crop&w=2400&q=58',
            'products.index'     => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=2400&q=65',
            'products.show'      => 'https://images.unsplash.com/photo-1612817288484-6f916006741a?auto=format&fit=crop&w=2400&q=65',
            'cart'               => 'https://images.unsplash.com/photo-1608248543803-6ec288bfef52?auto=format&fit=crop&w=2400&q=65',
            'checkout'           => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?auto=format&fit=crop&w=2400&q=65',
            'account'            => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=2400&q=65',
            'orders.show'        => 'https://images.unsplash.com/photo-1612817288484-6f916006741a?auto=format&fit=crop&w=2400&q=65',
            'invoices.download'  => 'https://images.unsplash.com/photo-1612817288484-6f916006741a?auto=format&fit=crop&w=2400&q=60',
            'login'              => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=2400&q=70',
            'register'           => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=2400&q=70',
        ],
    ],
];
