<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\UserRepository;
use App\Security\PasswordPolicy;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;

class ChangePasswordController
{
    public function showForm(Request $request): void
    {
        View::output('admin/change-password', [
            'error' => Flash::pull('change_password_error'),
            'minLength' => (new PasswordPolicy())->minLength(),
        ], 'admin/layout');
    }

    public function submit(Request $request): void
    {
        $password = (string) $request->input('password', '');
        $confirmation = (string) $request->input('password_confirmation', '');

        $errors = (new PasswordPolicy())->validate($password);
        if ($password !== $confirmation) {
            $errors[] = 'A confirmação deve ser igual à senha.';
        }
        if ($errors !== []) {
            Flash::set('change_password_error', implode(' ', $errors));
            Response::redirect('/admin/trocar-senha');
        }

        $userId = Auth::id();
        $repository = new UserRepository();
        $repository->updatePassword($userId, password_hash($password, PASSWORD_DEFAULT), false);

        (new AuditService())->log($userId, 'password_changed', $request->ip(), 'user', (string) $userId);

        Response::redirect('/admin');
    }
}
