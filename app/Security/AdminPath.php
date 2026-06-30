<?php

declare(strict_types=1);

namespace App\Security;

use App\Support\BasePath;

/**
 * Tracks the admin URL prefix (/portal/{token} in production, /admin as fallback)
 * and provides URL translation so all controllers and views remain unaware of
 * the token-based routing layer.
 */
class AdminPath
{
    private static string $prefix = '/admin';
    private static string $originalUri = '';

    public static function setPrefix(string $prefix): void
    {
        self::$prefix = rtrim($prefix, '/');
    }

    public static function getPrefix(): string
    {
        return self::$prefix;
    }

    /**
     * Stores the browser-facing URI (before REQUEST_URI is rewritten for routing).
     * Used by nav_active() to highlight the correct sidebar link.
     */
    public static function setOriginalUri(string $uri): void
    {
        self::$originalUri = strtok($uri, '?') ?: '/';
    }

    public static function getOriginalUri(): string
    {
        return self::$originalUri !== ''
            ? self::$originalUri
            : (strtok((string) ($_SERVER['REQUEST_URI'] ?? '/'), '?') ?: '/');
    }

    /**
     * Converts an admin path (e.g. "/admin/login") to a full public URL
     * using the current token prefix (e.g. "https://host/portal/{token}/login").
     */
    public static function url(string $adminPath): string
    {
        $tail = self::stripAdminPrefix($adminPath);
        return BasePath::get() . self::$prefix . $tail;
    }

    /**
     * Returns the prefix-relative path for use in Location headers.
     * e.g. "/admin/login" → "/portal/{token}/login"
     */
    public static function redirectPath(string $adminPath): string
    {
        $tail = self::stripAdminPrefix($adminPath);
        return self::$prefix . $tail;
    }

    private static function stripAdminPrefix(string $path): string
    {
        if (str_starts_with($path, '/admin')) {
            return substr($path, 6); // len('/admin') = 6
        }
        return $path;
    }
}
