<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'), // Default guard tetap 'web' (untuk admin)
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [ // Guard untuk Admin (tabel 'users')
            'driver' => 'session',
            'provider' => 'users',
        ],

        'user_account_guard' => [ // <--- TAMBAHKAN GUARD INI UNTUK USER BIASA
            'driver' => 'session',
            'provider' => 'user_accounts_provider', // Arahkan ke provider user_account
        ],
    ],



    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | If you have multiple user tables or models you may configure multiple
    | providers to represent the model / table. These providers may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [ // Provider untuk Admin (tabel 'users')
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        'user_accounts_provider' => [ // <--- TAMBAHKAN PROVIDER INI UNTUK USER BIASA
            'driver' => 'eloquent',
            'model' => App\Models\UserAccount::class, // Pastikan Anda punya model ini
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
        'user_accounts_provider' => [ // <--- TAMBAHKAN INI JIKA USER ACCOUNT BUTUH RESET PASSWORD
            'provider' => 'user_accounts_provider',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'), // Bisa pakai tabel yang sama
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
