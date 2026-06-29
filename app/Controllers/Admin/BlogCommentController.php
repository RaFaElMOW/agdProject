<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\BlogCommentRepository;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;

class BlogCommentController
{
    public function index(Request $request): void
    {
        View::output('admin/blog-comments', [
            'comments' => (new BlogCommentRepository())->allWithPostTitle(),
            'success' => Flash::pull('comment_success'),
        ], 'admin/layout');
    }

    public function approve(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new BlogCommentRepository())->setStatus($id, 'approved');
        (new AuditService())->log(Auth::id(), 'comment_approved', $request->ip(), 'blog_comment', (string) $id);
        Flash::set('comment_success', 'Comentário aprovado.');
        Response::redirect('/admin/blog/comentarios');
    }

    public function markSpam(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new BlogCommentRepository())->setStatus($id, 'spam');
        (new AuditService())->log(Auth::id(), 'comment_marked_spam', $request->ip(), 'blog_comment', (string) $id);
        Flash::set('comment_success', 'Comentário marcado como spam.');
        Response::redirect('/admin/blog/comentarios');
    }

    public function destroy(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new BlogCommentRepository())->delete($id);
        (new AuditService())->log(Auth::id(), 'comment_deleted', $request->ip(), 'blog_comment', (string) $id);
        Flash::set('comment_success', 'Comentário removido.');
        Response::redirect('/admin/blog/comentarios');
    }
}
