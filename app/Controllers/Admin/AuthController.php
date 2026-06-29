<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Services\AuthService;
use App\Support\Auth;
use App\Support\Flash;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function showLogin(Request $request): void
    {
        if (!empty($_SESSION['user_id'])) {
            Response::redirect('/admin');
        }

        View::output('admin/login', [
            'error' => Flash::pull('login_error'),
        ], 'admin/layout-guest');
    }

    public function login(Request $request): void
    {
        $email = (string) $request->input('email', '');
        $password = (string) $request->input('password', '');

        $result = $this->authService->login($email, $password, $request->ip(), $request->userAgent());

        if (!$result['ok']) {
            Flash::set('login_error', $result['message']);
            Response::redirect('/admin/login');
        }

        if ($result['must_change_password']) {
            Response::redirect('/admin/trocar-senha');
        }

        Response::redirect('/admin');
    }

    public function logout(Request $request): void
    {
        $this->authService->logout($request->ip());
        Response::redirect('/admin/login');
    }

    public function logoutGlobal(Request $request): void
    {
        $userId = Auth::id();
        if ($userId !== null) {
            $this->authService->logoutGlobal($userId, $request->ip());
        }
        Response::redirect('/admin/login');
    }
}
