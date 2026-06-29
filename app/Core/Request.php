<?php

namespace App\Core;

class Request
{
    private array $query;
    private array $body;
    private array $server;
    private array $cookies;
    private string $basePrefix;

    public function __construct(array $query, array $body, array $server, array $cookies, string $basePrefix = '')
    {
        $this->query = $query;
        $this->body = $body;
        $this->server = $server;
        $this->cookies = $cookies;
        $this->basePrefix = rtrim($basePrefix, '/');
    }

    /**
     * @param string $basePrefix Path prefix to strip before route matching — lets the same
     *   route table work whether the app sits at the domain root (cPanel) or in a subfolder
     *   (e.g. local XAMPP at /agdProject).
     */
    public static function capture(string $basePrefix = ''): self
    {
        return new self($_GET, $_POST, $_SERVER, $_COOKIE, $basePrefix);
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function path(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        if ($this->basePrefix !== '' && str_starts_with($path, $this->basePrefix)) {
            $path = substr($path, strlen($this->basePrefix));
        }

        $path = rtrim($path, '/');
        return $path === '' ? '/' : $path;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    public function only(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->input($key);
        }
        return $result;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function cookie(string $key, mixed $default = null): mixed
    {
        return $this->cookies[$key] ?? $default;
    }

    public function ip(): string
    {
        return $this->server['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public function userAgent(): string
    {
        return substr((string) ($this->server['HTTP_USER_AGENT'] ?? ''), 0, 255);
    }

    public function bearerToken(): ?string
    {
        $header = $this->server['HTTP_AUTHORIZATION'] ?? '';
        if (str_starts_with($header, 'Bearer ')) {
            return substr($header, 7);
        }
        return null;
    }
}
