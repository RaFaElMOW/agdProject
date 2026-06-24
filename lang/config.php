<?php

return [
    'default' => 'pt-BR',
    'fallback' => 'en',
    'cookie' => 'site_lang',
    'cookie_lifetime' => 60 * 60 * 24 * 365,
    'languages' => [
        'en'    => ['label' => 'English',       'short' => 'EN',    'file' => __DIR__ . '/en.php'],
        'pt-BR' => ['label' => 'Português (BR)', 'short' => 'PT-BR', 'file' => __DIR__ . '/pt-BR.php'],
        'de'    => ['label' => 'Deutsch',        'short' => 'DE',    'file' => __DIR__ . '/de.php'],
        'fr'    => ['label' => 'Français',       'short' => 'FR',    'file' => __DIR__ . '/fr.php'],
        'ja'    => ['label' => '日本語',           'short' => 'JA',    'file' => __DIR__ . '/ja.php'],
    ],

    // Country (ISO 3166-1 alpha-2) to language mapping, used when IP-based
    // geolocation is enabled. Countries not listed here fall back to
    // 'fallback' above (English), not to 'default'.
    'country_map' => [
        'BR' => 'pt-BR', 'PT' => 'pt-BR',
        'DE' => 'de', 'AT' => 'de', 'CH' => 'de',
        'FR' => 'fr', 'BE' => 'fr', 'LU' => 'fr',
        'JP' => 'ja',
        'US' => 'en', 'GB' => 'en', 'IE' => 'en', 'AU' => 'en', 'CA' => 'en', 'NZ' => 'en',
    ],
];
