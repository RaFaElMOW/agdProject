<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\MenuRepository;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;

class MenuController
{
    public function index(Request $request): void
    {
        $repository = new MenuRepository();
        View::output('admin/menus', [
            'menus' => $repository->all(),
            'success' => Flash::pull('menu_success'),
            'error' => Flash::pull('menu_error'),
        ], 'admin/layout');
    }

    public function create(Request $request): void
    {
        $repository = new MenuRepository();
        View::output('admin/menu-form', [
            'menu' => null,
            'parentOptions' => $this->parentOptions($repository),
        ], 'admin/layout');
    }

    public function store(Request $request): void
    {
        $repository = new MenuRepository();
        $id = $repository->create($this->fromRequest($request));
        (new AuditService())->log(Auth::id(), 'menu_created', $request->ip(), 'menu', (string) $id);
        Flash::set('menu_success', 'Item de menu criado com sucesso.');
        Response::redirect('/admin/menus');
    }

    public function edit(Request $request, array $params): void
    {
        $repository = new MenuRepository();
        $menu = $repository->find((int) $params['id']);
        if ($menu === null) {
            Response::notFound('Item de menu não encontrado.');
        }
        View::output('admin/menu-form', [
            'menu' => $menu,
            'parentOptions' => $this->parentOptions($repository, (int) $menu['id']),
        ], 'admin/layout');
    }

    public function update(Request $request, array $params): void
    {
        $repository = new MenuRepository();
        $id = (int) $params['id'];
        $repository->update($id, $this->fromRequest($request));
        (new AuditService())->log(Auth::id(), 'menu_updated', $request->ip(), 'menu', (string) $id);
        Flash::set('menu_success', 'Item de menu atualizado com sucesso.');
        Response::redirect('/admin/menus');
    }

    public function destroy(Request $request, array $params): void
    {
        $repository = new MenuRepository();
        $id = (int) $params['id'];
        $repository->delete($id);
        (new AuditService())->log(Auth::id(), 'menu_deleted', $request->ip(), 'menu', (string) $id);
        Flash::set('menu_success', 'Item de menu removido.');
        Response::redirect('/admin/menus');
    }

    private function fromRequest(Request $request): array
    {
        return [
            'location' => $request->input('location') === 'footer' ? 'footer' : 'header',
            'label' => trim((string) $request->input('label', '')),
            'url' => trim((string) $request->input('url', '')),
            'parent_id' => ($pid = (int) $request->input('parent_id', 0)) > 0 ? $pid : null,
            'sort_order' => (int) $request->input('sort_order', 0),
            'target_blank' => $request->input('target_blank') ? 1 : 0,
        ];
    }

    private function parentOptions(MenuRepository $repository, ?int $excludeId = null): array
    {
        return array_filter($repository->all(), function ($row) use ($excludeId) {
            return $row['parent_id'] === null && $row['id'] !== $excludeId;
        });
    }
}
