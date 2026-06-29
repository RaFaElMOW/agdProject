<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\UserRepository;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;

class ChangePasswordController
{
    public function showForm(Request $request): void
    {
        View::output('admin/change-password', [
            'error' => Flash::pull('change_password_error'),
        ], 'admin/layout');
    }

    public function submit(Request $request): void
    {
        $password = (string) $request->input('password', '');
        $confirmation = (string) $request->input('password_confirmation', '');

        if (strlen($password) < 10 || $password !== $confirmation) {
            Flash::set('change_password_error', 'A senha deve ter no mínimo 10 caracteres e a confirmação deve ser igual.');
            Response::redirect('/admin/trocar-senha');
        }

        $userId = Auth::id();
        $repository = new UserRepository();
        $repository->updatePassword($userId, password_hash($password, PASSWORD_DEFAULT), false);

        (new AuditService())->log($userId, 'password_changed', $request->ip(), 'user', (string) $userId);

        Response::redirect('/admin');
    }
}
