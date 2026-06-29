<?php

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Response;
use App\Services\RateLimiterService;

class RateLimitMiddleware implements MiddlewareInterface
{
    private string $routeKey;
    private int $maxAttempts;
    private int $decaySeconds;
    private RateLimiterService $limiter;

    public function __construct(string $routeKey, int $maxAttempts = 10, int $decaySeconds = 60)
    {
        $this->routeKey = $routeKey;
        $this->maxAttempts = $maxAttempts;
        $this->decaySeconds = $decaySeconds;
        $this->limiter = new RateLimiterService();
    }

    public function handle(Request $request, callable $next): mixed
    {
        $key = $this->routeKey . ':' . $request->ip();

        if (!$this->limiter->attempt($key, $this->maxAttempts, $this->decaySeconds)) {
            Response::status(429);
            echo 'Muitas tentativas. Aguarde um momento e tente novamente.';
            exit;
        }

        return $next($request);
    }
}
