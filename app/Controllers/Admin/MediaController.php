<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\MediaItemRepository;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;
use App\Support\ImageUploadHelper;
use App\Support\VideoUrl;

class MediaController
{
    public function index(Request $request): void
    {
        View::output('admin/media', [
            'items' => (new MediaItemRepository())->all(),
            'success' => Flash::pull('media_success'),
            'error' => Flash::pull('media_error'),
        ], 'admin/layout');
    }

    public function create(Request $request): void
    {
        View::output('admin/media-form', ['item' => null, 'error' => Flash::pull('media_error')], 'admin/layout');
    }

    public function store(Request $request): void
    {
        $this->save($request, null);
    }

    public function edit(Request $request, array $params): void
    {
        $item = (new MediaItemRepository())->find((int) $params['id']);
        if ($item === null) {
            Response::notFound('Item de mídia não encontrado.');
        }
        View::output('admin/media-form', ['item' => $item, 'error' => Flash::pull('media_error')], 'admin/layout');
    }

    public function update(Request $request, array $params): void
    {
        $this->save($request, (int) $params['id']);
    }

    public function destroy(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new MediaItemRepository())->delete($id);
        (new AuditService())->log(Auth::id(), 'media_item_deleted', $request->ip(), 'media_item', (string) $id);
        Flash::set('media_success', 'Item removido.');
        Response::redirect('/admin/midia');
    }

    private function save(Request $request, ?int $id): void
    {
        $repository = new MediaItemRepository();
        $existing = $id !== null ? $repository->find($id) : null;
        $type = $request->input('type') === 'video' ? 'video' : 'image';
        $redirectBack = $id !== null ? '/admin/midia/' . $id . '/editar' : '/admin/midia/novo';

        if ($type === 'video') {
            $url = trim((string) $request->input('url_or_path', ''));
            if ($url === '' || !VideoUrl::isAllowed($url)) {
                Flash::set('media_error', 'Link de vídeo inválido. Apenas YouTube, Vimeo, Facebook, Dailymotion e TikTok são permitidos.');
                Response::redirect($redirectBack);
            }
            $thumbnail = trim((string) $request->input('thumbnail', '')) ?: VideoUrl::thumbnail($url);
        } else {
            try {
                $stored = ImageUploadHelper::handle('image_file', $existing['url_or_path'] ?? null, 'media');
            } catch (\RuntimeException $e) {
                Flash::set('media_error', $e->getMessage());
                Response::redirect($redirectBack);
            }
            if ($stored === null) {
                Flash::set('media_error', 'Selecione uma imagem para enviar.');
                Response::redirect($redirectBack);
            }
            $url = $stored;
            $thumbnail = null;
        }

        $data = [
            'type' => $type,
            'title' => trim((string) $request->input('title', '')),
            'url_or_path' => $url,
            'thumbnail' => $thumbnail,
            'category' => trim((string) $request->input('category', '')),
            'sort_order' => (int) $request->input('sort_order', 0),
            'active' => $request->input('active') ? 1 : 0,
        ];

        if ($id === null) {
            $newId = $repository->create($data);
            (new AuditService())->log(Auth::id(), 'media_item_created', $request->ip(), 'media_item', (string) $newId);
        } else {
            $repository->update($id, $data);
            (new AuditService())->log(Auth::id(), 'media_item_updated', $request->ip(), 'media_item', (string) $id);
        }

        Flash::set('media_success', 'Item salvo com sucesso.');
        Response::redirect('/admin/midia');
    }
}
