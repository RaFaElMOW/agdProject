<?php

use App\Core\Env;
use App\Support\BasePath;
use App\Support\Csrf;

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        static $cache = [];

        [$file, $path] = array_pad(explode('.', $key, 2), 2, null);

        if (!isset($cache[$file])) {
            $configPath = __DIR__ . '/../Config/' . $file . '.php';
            $cache[$file] = is_file($configPath) ? require $configPath : [];
        }

        if ($path === null) {
            return $cache[$file];
        }

        $value = $cache[$file];
        foreach (explode('.', $path) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return Env::get($key, $default);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return Csrf::token();
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return Csrf::field();
    }
}

if (!function_exists('old')) {
    function old(string $key, string $default = ''): string
    {
        return e($_SESSION['_old_input'][$key] ?? $default);
    }
}

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        return config('app.url') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('admin_url')) {
    function admin_url(string $path): string
    {
        return BasePath::url($path);
    }
}

if (!function_exists('public_asset_url')) {
    /**
     * Resolves a stored asset path (e.g. "images/logo.png" or "/uploads/x.jpg") to a URL
     * that is correct regardless of deployment depth (cPanel domain root vs local XAMPP
     * subfolder) AND regardless of whether the current page was reached through a flat
     * URL or a rewritten pretty URL (e.g. /blog/slug). Public pages are flat files at the
     * project root, so SCRIPT_NAME always points at the real root prefix even when
     * REQUEST_URI looks deeper due to a rewrite.
     */
    function public_asset_url(string $path): string
    {
        if ($path === '' || str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return $path;
        }

        static $base = null;
        if ($base === null) {
            $base = rtrim(str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? ''))), '/');
        }

        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('nav_active')) {
    function nav_active(string $path, bool $exact = false): string
    {
        $current = rtrim((string) ($_SERVER['REQUEST_URI'] ?? ''), '/');
        $target = rtrim(admin_url($path), '/');
        $match = $exact ? ($current === $target) : str_starts_with($current, $target);
        return $match ? ' class="active"' : '';
    }
}
