<?php

return [
    'stripe_checkout' => [
        'secret_key' => env('STRIPE_CHECKOUT_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_CHECKOUT_WEBHOOK_SECRET'),
        'db' => [
            'name' => 'Stripe Checkout',
            'method' => 'stripe_checkout',
            'processor' => 'StripeCheckoutService',
            'settings' => null,
            'webhook_key' => null,
            'template' => null,
        ]
    ],
    'yookassa' => [
        'shop_id' => env('YOOKASSA_SHOP_ID'),
        'secret_key' => env('YOOKASSA_SECRET_KEY'),
        'db' => [
            'name' => 'ЮKassa',
            'method' => 'yookassa',
            'processor' => 'YookassaService',
            'settings' => null,
            'webhook_key' => null,
            'template' => null,
        ]
    ],
];
