<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\ProjectRepository;
use App\Services\AuditService;
use App\Services\UploadService;
use App\Support\Auth;
use App\Support\Flash;
use App\Support\ImageUploadHelper;
use App\Support\Paths;
use App\Support\Slug;

class ProjectController
{
    public function index(Request $request): void
    {
        View::output('admin/projects', [
            'projects' => (new ProjectRepository())->all(),
            'success' => Flash::pull('project_success'),
            'error' => Flash::pull('project_error'),
        ], 'admin/layout');
    }

    public function create(Request $request): void
    {
        View::output('admin/project-form', ['project' => null, 'gallery' => []], 'admin/layout');
    }

    public function store(Request $request): void
    {
        $this->save($request, null);
    }

    public function edit(Request $request, array $params): void
    {
        $repository = new ProjectRepository();
        $id = (int) $params['id'];
        $project = $repository->find($id);
        if ($project === null) {
            Response::notFound('Projeto não encontrado.');
        }
        View::output('admin/project-form', [
            'project' => $project,
            'gallery' => $repository->galleryFor($id),
        ], 'admin/layout');
    }

    public function update(Request $request, array $params): void
    {
        $this->save($request, (int) $params['id']);
    }

    public function destroy(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new ProjectRepository())->delete($id);
        (new AuditService())->log(Auth::id(), 'project_deleted', $request->ip(), 'project', (string) $id);
        Flash::set('project_success', 'Projeto removido.');
        Response::redirect('/admin/projetos');
    }

    public function addGalleryImage(Request $request, array $params): void
    {
        $projectId = (int) $params['id'];
        $repository = new ProjectRepository();
        if ($repository->find($projectId) === null) {
            Response::notFound('Projeto não encontrado.');
        }

        try {
            $service = new UploadService(Paths::uploads('projects'));
            $filename = $service->storeImage($_FILES['image'] ?? []);
            $repository->addGalleryImage($projectId, 'uploads/projects/' . $filename);
            Flash::set('project_success', 'Imagem adicionada à galeria.');
        } catch (\RuntimeException $e) {
            Flash::set('project_error', $e->getMessage());
        }

        Response::redirect('/admin/projetos/' . $projectId . '/editar');
    }

    public function deleteGalleryImage(Request $request, array $params): void
    {
        $projectId = (int) $params['id'];
        $imageId = (int) $params['imageId'];
        (new ProjectRepository())->deleteGalleryImage($imageId, $projectId);
        Flash::set('project_success', 'Imagem removida da galeria.');
        Response::redirect('/admin/projetos/' . $projectId . '/editar');
    }

    private function save(Request $request, ?int $id): void
    {
        $repository = new ProjectRepository();
        $existing = $id !== null ? $repository->find($id) : null;

        $name = trim((string) $request->input('name', ''));
        $slug = trim((string) $request->input('slug', '')) ?: Slug::make($name);
        $slug = Slug::make($slug);

        if ($repository->slugExists($slug, $id)) {
            $slug .= '-' . bin2hex(random_bytes(2));
        }

        try {
            $banner = ImageUploadHelper::handle('banner', $existing['banner'] ?? null, 'projects');
        } catch (\RuntimeException $e) {
            Flash::set('project_error', $e->getMessage());
            Response::redirect($id !== null ? '/admin/projetos/' . $id . '/editar' : '/admin/projetos/novo');
        }

        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => trim((string) $request->input('description', '')),
            'banner' => $banner,
            'status' => $request->input('status', 'active'),
            'external_link' => trim((string) $request->input('external_link', '')),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];

        if ($id === null) {
            $newId = $repository->create($data);
            (new AuditService())->log(Auth::id(), 'project_created', $request->ip(), 'project', (string) $newId);
        } else {
            $repository->update($id, $data);
            (new AuditService())->log(Auth::id(), 'project_updated', $request->ip(), 'project', (string) $id);
        }

        Flash::set('project_success', 'Projeto salvo com sucesso.');
        Response::redirect('/admin/projetos');
    }
}
