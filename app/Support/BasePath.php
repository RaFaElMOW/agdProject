<?php

namespace App\Support;

/**
 * Holds the URL prefix the app is served under (e.g. '' at a cPanel domain root,
 * or '/agdProject' for local XAMPP testing in a subfolder) so route definitions
 * and redirects can stay subfolder-agnostic.
 */
class BasePath
{
    private static string $prefix = '';

    public static function set(string $prefix): void
    {
        self::$prefix = rtrim($prefix, '/');
    }

    public static function get(): string
    {
        return self::$prefix;
    }

    public static function url(string $path): string
    {
        return self::$prefix . $path;
    }
}
