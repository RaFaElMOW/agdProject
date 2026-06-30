<?php

/**
 * Lightweight bootstrap for legacy public pages (about.php, header/footer partials, ...):
 * just autoload + env, no session/CSRF/router — those stay specific to /admin and /api.
 */

require_once __DIR__ . '/../vendor/autoload.php';

\App\Core\Env::load(__DIR__ . '/../.env');

$securitySettings = \App\Security\SecuritySettings::getInstance();
if ($securitySettings->getBool('maintenance_mode')) {
    http_response_code(503);
    header('Retry-After: 3600');
    echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="utf-8"><title>Manutenção</title></head><body style="font-family:sans-serif;text-align:center;padding:4rem 1rem;">'
        . '<p>' . htmlspecialchars($securitySettings->get('maintenance_message'), ENT_QUOTES, 'UTF-8') . '</p>'
        . '</body></html>';
    exit;
}
