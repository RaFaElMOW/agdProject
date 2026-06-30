<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Security\AdminPath;
use App\Security\AuditLogger;
use App\Security\RouteManager;
use App\Security\SecuritySettings;
use App\Support\Auth;
use App\Support\Flash;

class SecurityController
{
    /** Boolean (0/1) settings editable from the form. */
    private const BOOL_FIELDS = [
        'password_require_uppercase',
        'password_require_lowercase',
        'password_require_number',
        'password_require_special',
        'enable_mfa',
        'allow_multiple_sessions',
        'csrf_enabled',
        'session_regenerate_login',
        'audit_enabled',
        'maintenance_mode',
        'force_https',
        'cookie_secure',
        'cookie_http_only',
        'allow_admin_registration',
        'enable_security_headers',
    ];

    /** Numeric settings, with [min, max] bounds applied on save. */
    private const INT_FIELDS = [
        'admin_session_timeout' => [5, 1440],
        'max_login_attempts'    => [3, 20],
        'login_lock_minutes'    => [1, 1440],
        'password_min_length'   => [8, 128],
        'remember_login_days'   => [1, 365],
        'log_retention_days'    => [0, 3650],
    ];

    public function showForm(Request $request): void
    {
        $settings = SecuritySettings::getInstance();

        View::output('admin/security-settings', [
            'settings' => $settings->all(),
            'adminUrl' => admin_url('/admin/seguranca'),
            'currentPortalUrl' => AdminPath::url('/admin'),
            'success' => Flash::pull('security_success'),
            'error' => Flash::pull('security_error'),
        ], 'admin/layout');
    }

    public function submit(Request $request): void
    {
        $settings = SecuritySettings::getInstance();
        $userId = Auth::id();

        foreach (self::BOOL_FIELDS as $key) {
            $settings->set($key, $request->input($key) !== null ? '1' : '0', $userId);
        }

        foreach (self::INT_FIELDS as $key => [$min, $max]) {
            $value = (int) $request->input($key, $settings->getInt($key));
            $value = max($min, min($max, $value));
            $settings->set($key, (string) $value, $userId);
        }

        $sameSite = (string) $request->input('same_site_cookie', 'Lax');
        if (!in_array($sameSite, ['Strict', 'Lax', 'None'], true)) {
            $sameSite = 'Lax';
        }
        $settings->set('same_site_cookie', $sameSite, $userId);

        $maintenanceMessage = trim((string) $request->input('maintenance_message', ''));
        if ($maintenanceMessage !== '') {
            $settings->set('maintenance_message', $maintenanceMessage, $userId);
        }

        (new AuditLogger())->log($userId, 'security_settings_updated', $request->ip());

        Flash::set('security_success', 'Configurações de segurança salvas com sucesso.');
        Response::redirect('/admin/seguranca');
    }

    public function regenerateToken(Request $request, array $params = []): void
    {
        $manager = new RouteManager(SecuritySettings::getInstance());
        $userId = Auth::id();

        $newToken = $manager->regenerateToken($userId);

        (new AuditLogger())->log($userId, 'admin_route_token_regenerated', $request->ip());

        Flash::set('security_success', 'URL administrativa regenerada com sucesso. Você foi redirecionado para o novo endereço — atualize seus favoritos.');

        // The session survives (token regeneration does not touch user_id/token_version),
        // but the old /portal/{old-token} path 404s immediately, so we redirect through
        // the new token-gated path directly rather than via Response::redirect(), which
        // would still resolve against the now-stale AdminPath prefix held in this request.
        $newUrl = \App\Support\BasePath::url('/portal/' . $newToken . '/seguranca');
        header('Location: ' . $newUrl);
        exit;
    }
}
