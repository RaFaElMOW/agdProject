<?php

declare(strict_types=1);

namespace App\Security;

/**
 * Provides session configuration values sourced from SecuritySettings.
 * Called by bootstrap.php before session_start() and by SessionAuthMiddleware
 * for inactivity / absolute timeout enforcement.
 */
class SessionManager
{
    private SecuritySettings $settings;

    public function __construct(?SecuritySettings $settings = null)
    {
        $this->settings = $settings ?? SecuritySettings::getInstance();
    }

    /** Session inactivity timeout in seconds (0 = disabled). */
    public function inactivityTimeout(): int
    {
        return $this->settings->getInt('admin_session_timeout') * 60;
    }

    /** SameSite policy: Strict | Lax | None */
    public function sameSite(): string
    {
        $value = $this->settings->get('same_site_cookie');
        return in_array($value, ['Strict', 'Lax', 'None'], true) ? $value : 'Lax';
    }

    public function cookieSecure(): bool
    {
        return $this->settings->getBool('cookie_secure');
    }

    public function cookieHttpOnly(): bool
    {
        return $this->settings->getBool('cookie_http_only');
    }

    /** Whether session ID should be regenerated on successful login. */
    public function regenerateOnLogin(): bool
    {
        return $this->settings->getBool('session_regenerate_login');
    }
}
