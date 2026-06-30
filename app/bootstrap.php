<?php

declare(strict_types=1);

use App\Core\Env;
use App\Security\SecuritySettings;
use App\Security\SessionManager;

require __DIR__ . '/../vendor/autoload.php';

Env::load(__DIR__ . '/../.env');

$debug = config('app.debug');

date_default_timezone_set((string) config('app.timezone', 'UTC'));

error_reporting(E_ALL);
ini_set('display_errors', $debug ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../storage/logs/php-error.log');

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['SERVER_PORT'] ?? null) === '443');

if (session_status() === PHP_SESSION_NONE) {
    session_name((string) config('app.session.name', 'agd_session'));

    // Security Settings overrides session cookie policy when the DB is reachable;
    // these calls fail closed to the hardcoded defaults below if it isn't (e.g.
    // first install, before migrations have run).
    try {
        $sessionManager = new SessionManager(SecuritySettings::getInstance());
        $sameSite = $sessionManager->sameSite();
        $cookieSecure = $sessionManager->cookieSecure() || $isHttps;
        $cookieHttpOnly = $sessionManager->cookieHttpOnly();
    } catch (\Throwable) {
        $sameSite = 'Lax';
        $cookieSecure = $isHttps;
        $cookieHttpOnly = true;
    }

    session_set_cookie_params([
        'lifetime' => (int) config('app.session.lifetime_minutes', 120) * 60,
        'path' => '/',
        'domain' => '',
        'secure' => $cookieSecure,
        'httponly' => $cookieHttpOnly,
        'samesite' => $sameSite,
    ]);
    session_start();
}

set_exception_handler(function (\Throwable $e) use ($debug) {
    error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    if ($debug) {
        echo '<pre>' . htmlspecialchars((string) $e, ENT_QUOTES, 'UTF-8') . '</pre>';
    } else {
        echo 'Ocorreu um erro inesperado. Tente novamente mais tarde.';
    }
});
