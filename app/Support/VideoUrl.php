<?php

namespace App\Support;

/**
 * Validates video URLs against an explicit host allowlist before they're ever stored
 * or rendered as an embed — the SSRF/host-allowlist control called for in the media
 * module (never trust/fetch an arbitrary externally-supplied video URL, and never let
 * an admin-entered link point an embed at a host outside the platforms we actually
 * support).
 *
 * Validation rules, in order:
 *  1. Scheme must be exactly http or https (rejects javascript:, data:, vbscript:, etc.).
 *  2. Host must EXACT-match an entry below (case-insensitive) — not a substring/suffix
 *     check, so "youtube.com.evil.com" or "evil.com/youtube.com" never pass.
 *  3. parse_url() itself separates userinfo from host per RFC 3986, so a trick like
 *     "https://youtube.com@evil.com/" still resolves host="evil.com" and is rejected.
 */
class VideoUrl
{
    private const ALLOWED_HOSTS = [
        // YouTube
        'youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be',
        'youtube-nocookie.com', 'www.youtube-nocookie.com',
        // Vimeo
        'vimeo.com', 'www.vimeo.com', 'player.vimeo.com',
        // Facebook Video
        'facebook.com', 'www.facebook.com', 'fb.watch',
        // Dailymotion
        'dailymotion.com', 'www.dailymotion.com',
        // TikTok
        'tiktok.com', 'www.tiktok.com',
    ];

    private const ALLOWED_SCHEMES = ['http', 'https'];
    private const MAX_LENGTH = 2048;

    public static function isAllowed(string $url): bool
    {
        $url = trim($url);

        if ($url === '' || strlen($url) > self::MAX_LENGTH || preg_match('/[\x00-\x1f]/', $url)) {
            return false;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);
        if ($scheme === null || !in_array(strtolower($scheme), self::ALLOWED_SCHEMES, true)) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);
        return $host !== null && in_array(strtolower($host), self::ALLOWED_HOSTS, true);
    }

    public static function youtubeId(string $url): ?string
    {
        if (preg_match('#(?:youtube(?:-nocookie)?\.com/watch\?v=|youtu\.be/|youtube(?:-nocookie)?\.com/embed/)([a-zA-Z0-9_-]{6,})#', $url, $m)) {
            return $m[1];
        }
        return null;
    }

    public static function thumbnail(string $url): ?string
    {
        $id = self::youtubeId($url);
        return $id !== null ? "https://i.ytimg.com/vi/{$id}/hqdefault.jpg" : null;
    }
}
