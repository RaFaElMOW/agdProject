<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\ContactMessageRepository;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;

class ContactMessageController
{
    public function index(Request $request): void
    {
        $repository = new ContactMessageRepository();
        $messages = $repository->all();

        foreach ($messages as $m) {
            if ($m['status'] === 'new') {
                $repository->setStatus((int) $m['id'], 'read');
            }
        }

        View::output('admin/contact-messages', [
            'messages' => $messages,
            'success' => Flash::pull('contact_success'),
        ], 'admin/layout');
    }

    public function archive(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new ContactMessageRepository())->setStatus($id, 'archived');
        (new AuditService())->log(Auth::id(), 'contact_message_archived', $request->ip(), 'contact_message', (string) $id);
        Flash::set('contact_success', 'Mensagem arquivada.');
        Response::redirect('/admin/mensagens');
    }

    public function destroy(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new ContactMessageRepository())->delete($id);
        (new AuditService())->log(Auth::id(), 'contact_message_deleted', $request->ip(), 'contact_message', (string) $id);
        Flash::set('contact_success', 'Mensagem removida.');
        Response::redirect('/admin/mensagens');
    }
}
