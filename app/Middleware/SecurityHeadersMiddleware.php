<?php

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Security\SecuritySettings;

/**
 * Baseline headers (X-Content-Type-Options, X-Frame-Options, Referrer-Policy,
 * Permissions-Policy, HSTS) are set site-wide in .htaccess, so they apply even to
 * static assets and legacy public pages that don't go through this middleware yet.
 * This middleware only owns the CSP, since it's app-context-specific (the admin
 * panel needs no YouTube/Vimeo/PayPal frame-src that the public donation pages will).
 *
 * The whole CSP can be disabled via Security Settings (enable_security_headers) for
 * emergency debugging, but ships enabled by default.
 */
class SecurityHeadersMiddleware implements MiddlewareInterface
{
    private static string $nonce = '';

    public function handle(Request $request, callable $next): mixed
    {
        $settings = SecuritySettings::getInstance();

        if (!$settings->getBool('enable_security_headers')) {
            return $next($request);
        }

        $nonce = self::currentNonce();

        header("Content-Security-Policy: default-src 'self'; " .
            "script-src 'self' 'nonce-{$nonce}'; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
            "font-src 'self' https://fonts.gstatic.com; " .
            // https: (any host) is allowed here so the blog editor's live preview can
            // render an externally-hosted image an admin inserts by URL — this is an
            // authenticated-admin-only convenience, not exposed to the public site.
            "img-src 'self' data: https:; " .
            "connect-src 'self'; frame-ancestors 'self'");

        return $next($request);
    }

    /**
     * Per-request CSP nonce, generated once and reused by every <script nonce="...">
     * tag rendered while handling this request (via the csp_nonce() helper).
     */
    public static function currentNonce(): string
    {
        if (self::$nonce === '') {
            self::$nonce = bin2hex(random_bytes(16));
        }
        return self::$nonce;
    }
}
