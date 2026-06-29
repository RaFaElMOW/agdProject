<?php

namespace App\Core;

interface MiddlewareInterface
{
    /**
     * @param callable $next Calls the next middleware/handler in the chain.
     */
    public function handle(Request $request, callable $next): mixed;
}
