<?php

namespace App\Services;

use App\Repositories\RateLimitRepository;

class RateLimiterService
{
    private RateLimitRepository $repository;

    public function __construct(?RateLimitRepository $repository = null)
    {
        $this->repository = $repository ?? new RateLimitRepository();
    }

    /**
     * Returns true and registers the hit if the caller is still within the allowed limit.
     * Returns false (without registering) once the limit has been exceeded for the window.
     */
    public function attempt(string $key, int $maxAttempts, int $decaySeconds): bool
    {
        $windowStart = intdiv(time(), $decaySeconds) * $decaySeconds;
        $count = $this->repository->hit($key, $windowStart);
        return $count <= $maxAttempts;
    }
}
