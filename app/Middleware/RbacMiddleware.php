<?php

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Response;
use App\Repositories\AuditLogRepository;
use App\Support\Auth;

class RbacMiddleware implements MiddlewareInterface
{
    private string $permission;

    public function __construct(string $permission)
    {
        $this->permission = $permission;
    }

    public function handle(Request $request, callable $next): mixed
    {
        if (!Auth::can($this->permission)) {
            (new AuditLogRepository())->log(
                Auth::id(),
                'access_denied',
                'permission',
                $this->permission,
                $request->ip(),
                ['path' => $request->path()]
            );
            Response::forbidden('Você não tem permissão para acessar este recurso.');
        }

        return $next($request);
    }
}
