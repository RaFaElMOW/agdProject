<?php

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Response;
use App\Support\Csrf;

class CsrfMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): mixed
    {
        if ($request->isPost() && !Csrf::verify($request->input('_csrf'))) {
            Response::forbidden('Token CSRF inválido ou ausente. Recarregue a página e tente novamente.');
        }

        return $next($request);
    }
}
