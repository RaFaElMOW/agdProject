<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Security\PasswordPolicy;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;

class UserController
{
    public function index(Request $request): void
    {
        $users = new UserRepository();
        $roles = new RoleRepository();

        $list = array_map(function ($user) use ($roles) {
            $user['role_names'] = $roles->namesForUser((int) $user['id']);
            return $user;
        }, $users->all());

        View::output('admin/users', [
            'users' => $list,
            'success' => Flash::pull('user_success'),
            'error' => Flash::pull('user_error'),
            'generatedPassword' => Flash::pull('user_generated_password'),
        ], 'admin/layout');
    }

    public function create(Request $request): void
    {
        View::output('admin/user-form', [
            'user' => null,
            'roles' => (new RoleRepository())->all(),
            'selectedRoleIds' => [],
            'minLength' => (new PasswordPolicy())->minLength(),
        ], 'admin/layout');
    }

    public function store(Request $request): void
    {
        $name = trim((string) $request->input('name', ''));
        $email = mb_strtolower(trim((string) $request->input('email', '')));
        $password = (string) $request->input('password', '');
        $roleIds = array_map('intval', (array) ($_POST['roles'] ?? []));

        $passwordErrors = (new PasswordPolicy())->validate($password);
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $passwordErrors !== []) {
            Flash::set('user_error', $name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)
                ? 'Verifique os dados: nome e e-mail válido são obrigatórios.'
                : implode(' ', $passwordErrors));
            Response::redirect('/admin/usuarios/novo');
        }

        $users = new UserRepository();
        if ($users->findByEmail($email) !== null) {
            Flash::set('user_error', 'Já existe um usuário com este e-mail.');
            Response::redirect('/admin/usuarios/novo');
        }

        $id = $users->create([
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'status' => 'active',
            'must_change_password' => 1,
            'created_by' => Auth::id(),
        ]);

        (new RoleRepository())->syncForUser($id, $roleIds);
        (new AuditService())->log(Auth::id(), 'user_created', $request->ip(), 'user', (string) $id);

        Flash::set('user_success', 'Usuário criado com sucesso.');
        Response::redirect('/admin/usuarios');
    }

    public function edit(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        $user = (new UserRepository())->findById($id);
        if ($user === null) {
            Response::notFound('Usuário não encontrado.');
        }

        View::output('admin/user-form', [
            'user' => $user,
            'roles' => (new RoleRepository())->all(),
            'selectedRoleIds' => (new RoleRepository())->idsForUser($id),
        ], 'admin/layout');
    }

    public function update(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        $name = trim((string) $request->input('name', ''));
        $email = mb_strtolower(trim((string) $request->input('email', '')));
        $status = in_array($request->input('status'), ['active', 'inactive', 'blocked'], true) ? $request->input('status') : 'active';
        $roleIds = array_map('intval', (array) ($_POST['roles'] ?? []));

        if ($id === Auth::id() && $status !== 'active') {
            Flash::set('user_error', 'Você não pode bloquear/desativar a si mesmo.');
            Response::redirect('/admin/usuarios/' . $id . '/editar');
        }

        $users = new UserRepository();
        $existing = $users->findByEmail($email);
        if ($existing !== null && (int) $existing['id'] !== $id) {
            Flash::set('user_error', 'Já existe outro usuário com este e-mail.');
            Response::redirect('/admin/usuarios/' . $id . '/editar');
        }

        $users->updateProfile($id, $name, $email, $status);
        if ($status !== 'active') {
            $users->bumpTokenVersion($id); // kills any active session immediately
        }
        (new RoleRepository())->syncForUser($id, $roleIds);
        (new AuditService())->log(Auth::id(), 'user_updated', $request->ip(), 'user', (string) $id, ['status' => $status]);

        Flash::set('user_success', 'Usuário atualizado com sucesso.');
        Response::redirect('/admin/usuarios');
    }

    public function setStatus(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        $status = $request->input('status');
        if (!in_array($status, ['active', 'inactive', 'blocked'], true)) {
            Response::redirect('/admin/usuarios');
        }

        if ($id === Auth::id() && $status !== 'active') {
            Flash::set('user_error', 'Você não pode bloquear/desativar a si mesmo.');
            Response::redirect('/admin/usuarios');
        }

        $users = new UserRepository();
        $users->setStatus($id, $status);
        if ($status !== 'active') {
            $users->bumpTokenVersion($id);
        }
        (new AuditService())->log(Auth::id(), 'user_status_changed', $request->ip(), 'user', (string) $id, ['status' => $status]);

        Flash::set('user_success', 'Status do usuário atualizado.');
        Response::redirect('/admin/usuarios');
    }

    public function resetPassword(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        $users = new UserRepository();
        $user = $users->findById($id);
        if ($user === null) {
            Response::notFound('Usuário não encontrado.');
        }

        $newPassword = (new PasswordPolicy())->generateSecurePassword();
        $users->updatePassword($id, password_hash($newPassword, PASSWORD_DEFAULT), true);
        $users->bumpTokenVersion($id);

        (new AuditService())->log(Auth::id(), 'user_password_reset', $request->ip(), 'user', (string) $id);

        Flash::set('user_success', 'Senha redefinida para ' . $user['email'] . '. O usuário deverá trocá-la no próximo login.');
        Flash::set('user_generated_password', $newPassword);
        Response::redirect('/admin/usuarios');
    }
}
