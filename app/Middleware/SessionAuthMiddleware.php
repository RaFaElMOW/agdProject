<?php

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Response;
use App\Repositories\UserRepository;
use App\Security\SecuritySettings;
use App\Support\Auth;

class SessionAuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): mixed
    {
        $userId = $_SESSION['user_id'] ?? null;
        $sessionTokenVersion = $_SESSION['token_version'] ?? null;

        if ($userId === null) {
            Response::redirect('/admin/login');
        }

        $settings = SecuritySettings::getInstance();
        $timeoutSeconds = $settings->getInt('admin_session_timeout') * 60;
        $lastActivity = $_SESSION['last_activity'] ?? null;

        if ($timeoutSeconds > 0 && $lastActivity !== null && (time() - (int) $lastActivity) > $timeoutSeconds) {
            session_unset();
            session_destroy();
            Response::redirect('/admin/login');
        }

        $repository = new UserRepository();
        $user = $repository->findById((int) $userId);

        if ($user === null || $user['status'] !== 'active' || (int) $user['token_version'] !== (int) $sessionTokenVersion) {
            session_unset();
            session_destroy();
            Response::redirect('/admin/login');
        }

        $_SESSION['last_activity'] = time();

        $permissions = $repository->permissionSlugsFor((int) $user['id']);
        Auth::setUser($user, $permissions);

        return $next($request);
    }
}
