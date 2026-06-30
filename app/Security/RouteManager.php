<?php

declare(strict_types=1);

namespace App\Security;

/**
 * Manages the admin route token: generation, validation and regeneration.
 * Token requirements: random_bytes(), ≥ 48 hex chars, high entropy.
 */
class RouteManager
{
    private SecuritySettings $settings;

    public function __construct(?SecuritySettings $settings = null)
    {
        $this->settings = $settings ?? SecuritySettings::getInstance();
    }

    public function getToken(): string
    {
        return $this->settings->get('admin_route_token');
    }

    /**
     * Generates a fresh token, persists it, and returns it.
     * The old token is invalidated immediately.
     */
    public function regenerateToken(?int $updatedBy = null): string
    {
        $newToken = bin2hex(random_bytes(24)); // 48 hex chars
        $this->settings->set('admin_route_token', $newToken, $updatedBy);
        return $newToken;
    }

    /**
     * Timing-safe token comparison.
     */
    public function validateToken(string $candidate): bool
    {
        $stored = $this->getToken();

        if ($stored === '' || strlen($stored) < 48) {
            return false;
        }

        return hash_equals($stored, $candidate);
    }
}
