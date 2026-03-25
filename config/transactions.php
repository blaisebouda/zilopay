<?php

return [
    'min_transfer' => env('MIN_TRANSFER', 100), // Minimum transfer amount (default: 100)
    'max_transfer' => env('MAX_TRANSFER', 10000000), // Maximum transfer amount (default: 10,000,000)
    'transfer_fee_percent' => env('TRANSFER_FEE_PERCENT', 0.5), // Transfer fee percentage
    'transfer_fixed_fee' => env('TRANSFER_FIXED_FEE', 0), // Transfer fix fee
    'max_transfer_fee' => env('MAX_TRANSFER_FEE', 5000), // Maximum transfer fee cap
    'min_deposit' => env('MIN_DEPOSIT', 100),
    'max_deposit' => env('MAX_DEPOSIT', 10000000),
    'min_withdrawal' => env('MIN_WITHDRAWAL', 100),
    'max_withdrawal' => env('MAX_WITHDRAWAL', 500000),

    'gateways' => [
        'orange_money' => [
            'enabled' => env('ORANGE_MONEY_ENABLED', true),
            'base_url' => env('ORANGE_MONEY_BASE_URL', 'https://api.orange.com'),
            'client_id' => env('ORANGE_MONEY_CLIENT_ID'),
            'client_secret' => env('ORANGE_MONEY_CLIENT_SECRET'),
            'merchant_id' => env('ORANGE_MONEY_MERCHANT_ID'),
        ],
        'wave' => [
            'enabled' => env('WAVE_ENABLED', false),
        ],
        'mtn_momo' => [
            'enabled' => env('MTN_MOMO_ENABLED', false),
        ],
    ],
];
