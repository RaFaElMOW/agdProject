<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\TeamMemberRepository;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;
use App\Support\ImageUploadHelper;

class TeamController
{
    public function index(Request $request): void
    {
        View::output('admin/team', [
            'members' => (new TeamMemberRepository())->all(),
            'success' => Flash::pull('team_success'),
            'error' => Flash::pull('team_error'),
        ], 'admin/layout');
    }

    public function create(Request $request): void
    {
        View::output('admin/team-form', ['member' => null, 'error' => Flash::pull('team_error')], 'admin/layout');
    }

    public function store(Request $request): void
    {
        $this->save($request, null);
    }

    public function edit(Request $request, array $params): void
    {
        $member = (new TeamMemberRepository())->find((int) $params['id']);
        if ($member === null) {
            Response::notFound('Membro da equipe não encontrado.');
        }
        View::output('admin/team-form', ['member' => $member, 'error' => Flash::pull('team_error')], 'admin/layout');
    }

    public function update(Request $request, array $params): void
    {
        $this->save($request, (int) $params['id']);
    }

    public function destroy(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new TeamMemberRepository())->delete($id);
        (new AuditService())->log(Auth::id(), 'team_member_deleted', $request->ip(), 'team_member', (string) $id);
        Flash::set('team_success', 'Membro removido.');
        Response::redirect('/admin/equipe');
    }

    private function save(Request $request, ?int $id): void
    {
        $repository = new TeamMemberRepository();
        $existing = $id !== null ? $repository->find($id) : null;

        try {
            $photo = ImageUploadHelper::handle('photo', $existing['photo'] ?? null, 'team');
        } catch (\RuntimeException $e) {
            Flash::set('team_error', $e->getMessage());
            Response::redirect($id !== null ? '/admin/equipe/' . $id . '/editar' : '/admin/equipe/novo');
        }

        $data = [
            'name' => trim((string) $request->input('name', '')),
            'role' => trim((string) $request->input('role', '')),
            'photo' => $photo,
            'bio' => trim((string) $request->input('bio', '')),
            'facebook' => trim((string) $request->input('facebook', '')),
            'instagram' => trim((string) $request->input('instagram', '')),
            'twitter' => trim((string) $request->input('twitter', '')),
            'linkedin' => trim((string) $request->input('linkedin', '')),
            'sort_order' => (int) $request->input('sort_order', 0),
            'active' => $request->input('active') ? 1 : 0,
        ];

        if ($id === null) {
            $newId = $repository->create($data);
            (new AuditService())->log(Auth::id(), 'team_member_created', $request->ip(), 'team_member', (string) $newId);
        } else {
            $repository->update($id, $data);
            (new AuditService())->log(Auth::id(), 'team_member_updated', $request->ip(), 'team_member', (string) $id);
        }

        Flash::set('team_success', 'Membro salvo com sucesso.');
        Response::redirect('/admin/equipe');
    }
}
