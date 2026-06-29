<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\TestimonialRepository;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;
use App\Support\ImageUploadHelper;

class TestimonialController
{
    public function index(Request $request): void
    {
        View::output('admin/testimonials', [
            'testimonials' => (new TestimonialRepository())->all(),
            'success' => Flash::pull('testimonial_success'),
            'error' => Flash::pull('testimonial_error'),
        ], 'admin/layout');
    }

    public function create(Request $request): void
    {
        View::output('admin/testimonial-form', ['testimonial' => null, 'error' => Flash::pull('testimonial_error')], 'admin/layout');
    }

    public function store(Request $request): void
    {
        $this->save($request, null);
    }

    public function edit(Request $request, array $params): void
    {
        $testimonial = (new TestimonialRepository())->find((int) $params['id']);
        if ($testimonial === null) {
            Response::notFound('Depoimento não encontrado.');
        }
        View::output('admin/testimonial-form', ['testimonial' => $testimonial, 'error' => Flash::pull('testimonial_error')], 'admin/layout');
    }

    public function update(Request $request, array $params): void
    {
        $this->save($request, (int) $params['id']);
    }

    public function destroy(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new TestimonialRepository())->delete($id);
        (new AuditService())->log(Auth::id(), 'testimonial_deleted', $request->ip(), 'testimonial', (string) $id);
        Flash::set('testimonial_success', 'Depoimento removido.');
        Response::redirect('/admin/depoimentos');
    }

    private function save(Request $request, ?int $id): void
    {
        $repository = new TestimonialRepository();
        $existing = $id !== null ? $repository->find($id) : null;

        try {
            $photo = ImageUploadHelper::handle('photo', $existing['photo'] ?? null, 'testimonials');
        } catch (\RuntimeException $e) {
            Flash::set('testimonial_error', $e->getMessage());
            Response::redirect($id !== null ? '/admin/depoimentos/' . $id . '/editar' : '/admin/depoimentos/novo');
        }

        $data = [
            'name' => trim((string) $request->input('name', '')),
            'role' => trim((string) $request->input('role', '')),
            'photo' => $photo,
            'text' => trim((string) $request->input('text', '')),
            'youtube_url' => trim((string) $request->input('youtube_url', '')),
            'sort_order' => (int) $request->input('sort_order', 0),
            'active' => $request->input('active') ? 1 : 0,
        ];

        if ($id === null) {
            $newId = $repository->create($data);
            (new AuditService())->log(Auth::id(), 'testimonial_created', $request->ip(), 'testimonial', (string) $newId);
        } else {
            $repository->update($id, $data);
            (new AuditService())->log(Auth::id(), 'testimonial_updated', $request->ip(), 'testimonial', (string) $id);
        }

        Flash::set('testimonial_success', 'Depoimento salvo com sucesso.');
        Response::redirect('/admin/depoimentos');
    }
}
