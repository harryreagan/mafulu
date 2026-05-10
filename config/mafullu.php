<?php

return [
    'wallets' => [
        'BTC' => env('BTC_ADDRESS', ''),
        'USDT' => env('USDT_ADDRESS', ''),
    ],

    'rates' => [
        'BTC' => (float) env('BTC_USD_RATE', 85000),
        'USDT' => 1.0,
    ],

    'admin_password' => env('ADMIN_PASSWORD', ''),
    'admin_email' => env('ADMIN_EMAIL'),
    'admin_session_key' => 'mafullu_admin_authenticated',

    'testimonials' => [
        [
            'quote' => 'Mafullu feels calm and trustworthy. I paid, uploaded proof, and had my file without friction.',
            'author' => 'Naomi K.',
            'role' => 'Template buyer',
        ],
        [
            'quote' => 'The checkout is simple enough that I never had to guess what happens next.',
            'author' => 'Daniel M.',
            'role' => 'Course customer',
        ],
        [
            'quote' => 'The products feel curated, not dumped into a generic marketplace.',
            'author' => 'Aisha R.',
            'role' => 'Ebook reader',
        ],
    ],

    'trust_points' => [
        'Manual payment review before delivery',
        'Single-use private download links',
        'Optional coupon support at checkout',
        '48-hour expiry with admin renewal',
    ],
];

