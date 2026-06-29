<?php

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;

/**
 * Baseline headers (X-Content-Type-Options, X-Frame-Options, Referrer-Policy,
 * Permissions-Policy, HSTS) are set site-wide in .htaccess, so they apply even to
 * static assets and legacy public pages that don't go through this middleware yet.
 * This middleware only owns the CSP, since it's app-context-specific (the admin
 * panel needs no YouTube/Vimeo/PayPal frame-src that the public donation pages will).
 */
class SecurityHeadersMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): mixed
    {
        header("Content-Security-Policy: default-src 'self'; " .
            "script-src 'self' 'unsafe-inline'; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
            "font-src 'self' https://fonts.gstatic.com; " .
            "img-src 'self' data:; " .
            "connect-src 'self'; frame-ancestors 'self'");

        return $next($request);
    }
}
