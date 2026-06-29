<?php

namespace App\Support;

/**
 * Generalizes the relative-path allowlist pattern already used in set-language.php
 * so every redirect in the app is protected against open-redirect.
 */
class UrlAllowlist
{
    public static function sanitizeInternal(string $path, string $fallback = '/admin/'): string
    {
        if ($path === '' || $path[0] !== '/' || str_starts_with($path, '//')) {
            return $fallback;
        }

        // Path + optional query string only: still requires a leading '/' (checked above),
        // still no scheme/host can appear (':' and '//' aren't in the allowed charset, so
        // "https://evil.com" or "//evil.com" never pass), still no quotes/angle-brackets/etc.
        if (preg_match('#^/[a-zA-Z0-9_\-/\.\?=&%]*$#', $path) !== 1) {
            return $fallback;
        }

        if (str_contains($path, '..')) {
            return $fallback;
        }

        return $path;
    }
}
