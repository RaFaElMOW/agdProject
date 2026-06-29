<?php

namespace App\Support;

class Auth
{
    private static ?array $user = null;
    private static array $permissions = [];

    public static function setUser(?array $user, array $permissions = []): void
    {
        self::$user = $user;
        self::$permissions = $permissions;
    }

    public static function user(): ?array
    {
        return self::$user;
    }

    public static function id(): ?int
    {
        return self::$user['id'] ?? null;
    }

    public static function check(): bool
    {
        return self::$user !== null;
    }

    public static function can(string $permissionSlug): bool
    {
        return in_array($permissionSlug, self::$permissions, true);
    }
}
