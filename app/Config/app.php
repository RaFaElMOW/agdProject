<?php

use App\Core\Env;

return [
    'env' => Env::get('APP_ENV', 'production'),
    'debug' => (bool) Env::get('APP_DEBUG', false),
    'url' => rtrim((string) Env::get('APP_URL', ''), '/'),
    'timezone' => Env::get('APP_TIMEZONE', 'UTC'),

    'session' => [
        'name' => Env::get('SESSION_NAME', 'agd_session'),
        'lifetime_minutes' => (int) Env::get('SESSION_LIFETIME', 120),
    ],

    'jwt' => [
        'secret' => Env::get('JWT_SECRET'),
        'ttl' => (int) Env::get('JWT_TTL', 900),
        'refresh_ttl' => (int) Env::get('JWT_REFRESH_TTL', 1209600),
    ],

    'migrate_token' => Env::get('MIGRATE_TOKEN'),

    'auth' => [
        'max_failed_attempts' => 5,
        'lockout_minutes' => 15,
    ],
];
