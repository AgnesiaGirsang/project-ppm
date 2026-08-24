<?php

use App\Models\Pegawai;

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'pegawais'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'pegawais',
        ],
    ],

    'providers' => [
        'pegawais' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', Pegawai::class),
        ],
    ],

    'passwords' => [
        'pegawais' => [
            'provider' => 'pegawais',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
