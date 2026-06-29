<?php

/**
 * Lightweight bootstrap for legacy public pages (about.php, header/footer partials, ...):
 * just autoload + env, no session/CSRF/router — those stay specific to /admin and /api.
 */

require_once __DIR__ . '/../vendor/autoload.php';

\App\Core\Env::load(__DIR__ . '/../.env');
