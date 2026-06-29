<?php

namespace App\Support;

use App\Repositories\SettingsRepository;

/**
 * Read-side cache for the `settings` table. Used both by the admin panel and by the
 * legacy public pages (about.php, header/footer partials, ...) as they're wired in.
 */
class Settings
{
    private static ?array $cache = null;

    public static function get(string $key, ?string $default = null): ?string
    {
        if (self::$cache === null) {
            self::$cache = self::loadSafely();
        }
        return self::$cache[$key] ?? $default;
    }

    public static function refresh(): void
    {
        self::$cache = null;
    }

    private static function loadSafely(): array
    {
        try {
            return (new SettingsRepository())->all();
        } catch (\Throwable) {
            // Public pages must keep rendering with their current hardcoded fallback
            // even if the DB is briefly unavailable or not migrated yet.
            return [];
        }
    }
}
