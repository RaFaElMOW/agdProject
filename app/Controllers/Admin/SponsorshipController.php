<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\SponsorshipCardRepository;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;
use App\Support\ImageUploadHelper;

class SponsorshipController
{
    public function index(Request $request): void
    {
        View::output('admin/sponsorship', [
            'cards' => (new SponsorshipCardRepository())->all(),
            'success' => Flash::pull('sponsorship_success'),
            'error' => Flash::pull('sponsorship_error'),
        ], 'admin/layout');
    }

    public function create(Request $request): void
    {
        View::output('admin/sponsorship-form', ['card' => null, 'error' => Flash::pull('sponsorship_error')], 'admin/layout');
    }

    public function store(Request $request): void
    {
        $this->save($request, null);
    }

    public function edit(Request $request, array $params): void
    {
        $card = (new SponsorshipCardRepository())->find((int) $params['id']);
        if ($card === null) {
            Response::notFound('Card de apadrinhamento não encontrado.');
        }
        View::output('admin/sponsorship-form', ['card' => $card, 'error' => Flash::pull('sponsorship_error')], 'admin/layout');
    }

    public function update(Request $request, array $params): void
    {
        $this->save($request, (int) $params['id']);
    }

    public function destroy(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new SponsorshipCardRepository())->delete($id);
        (new AuditService())->log(Auth::id(), 'sponsorship_card_deleted', $request->ip(), 'sponsorship_card', (string) $id);
        Flash::set('sponsorship_success', 'Card removido.');
        Response::redirect('/admin/apadrinhamento');
    }

    private function save(Request $request, ?int $id): void
    {
        $repository = new SponsorshipCardRepository();
        $existing = $id !== null ? $repository->find($id) : null;

        try {
            $image = ImageUploadHelper::handle('image', $existing['image'] ?? null, 'sponsorship');
        } catch (\RuntimeException $e) {
            Flash::set('sponsorship_error', $e->getMessage());
            Response::redirect($id !== null ? '/admin/apadrinhamento/' . $id . '/editar' : '/admin/apadrinhamento/novo');
        }

        $data = [
            'title' => trim((string) $request->input('title', '')),
            'description' => trim((string) $request->input('description', '')),
            'value' => trim((string) $request->input('value', '')),
            'currency' => trim((string) $request->input('currency', 'USD')),
            'image' => $image,
            'icon' => trim((string) $request->input('icon', '')),
            'cta_link' => trim((string) $request->input('cta_link', '')),
            'sort_order' => (int) $request->input('sort_order', 0),
            'status' => $request->input('status', 'active'),
        ];

        if ($id === null) {
            $newId = $repository->create($data);
            (new AuditService())->log(Auth::id(), 'sponsorship_card_created', $request->ip(), 'sponsorship_card', (string) $newId);
        } else {
            $repository->update($id, $data);
            (new AuditService())->log(Auth::id(), 'sponsorship_card_updated', $request->ip(), 'sponsorship_card', (string) $id);
        }

        Flash::set('sponsorship_success', 'Card salvo com sucesso.');
        Response::redirect('/admin/apadrinhamento');
    }
}
