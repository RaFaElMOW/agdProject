<?php

declare(strict_types=1);

namespace App\Security;

/**
 * Exposes brute-force protection settings from SecuritySettings.
 * AuthService reads these instead of the static config/app.php values,
 * making limits configurable at runtime via the Security Settings panel.
 */
class LoginProtection
{
    private SecuritySettings $settings;

    public function __construct(?SecuritySettings $settings = null)
    {
        $this->settings = $settings ?? SecuritySettings::getInstance();
    }

    /** Maximum consecutive failures before the account is locked. */
    public function maxAttempts(): int
    {
        return max(1, $this->settings->getInt('max_login_attempts'));
    }

    /** How long (seconds) the account stays locked after exceeding maxAttempts(). */
    public function lockDurationSeconds(): int
    {
        return max(60, $this->settings->getInt('login_lock_minutes') * 60);
    }

    /** Convenience: lock expiry timestamp from now. */
    public function lockUntilTimestamp(): int
    {
        return time() + $this->lockDurationSeconds();
    }

    /** Formatted datetime for DB storage: "Y-m-d H:i:s". */
    public function lockUntilDatetime(): string
    {
        return date('Y-m-d H:i:s', $this->lockUntilTimestamp());
    }
}
