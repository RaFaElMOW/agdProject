<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\BookRepository;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;
use App\Support\ImageUploadHelper;

class BookController
{
    public function index(Request $request): void
    {
        View::output('admin/books', [
            'books' => (new BookRepository())->all(),
            'success' => Flash::pull('book_success'),
            'error' => Flash::pull('book_error'),
        ], 'admin/layout');
    }

    public function create(Request $request): void
    {
        View::output('admin/book-form', ['book' => null, 'error' => Flash::pull('book_error')], 'admin/layout');
    }

    public function store(Request $request): void
    {
        $this->save($request, null);
    }

    public function edit(Request $request, array $params): void
    {
        $book = (new BookRepository())->find((int) $params['id']);
        if ($book === null) {
            Response::notFound('Livro não encontrado.');
        }
        View::output('admin/book-form', ['book' => $book, 'error' => Flash::pull('book_error')], 'admin/layout');
    }

    public function update(Request $request, array $params): void
    {
        $this->save($request, (int) $params['id']);
    }

    public function destroy(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new BookRepository())->delete($id);
        (new AuditService())->log(Auth::id(), 'book_deleted', $request->ip(), 'book', (string) $id);
        Flash::set('book_success', 'Livro removido.');
        Response::redirect('/admin/livros');
    }

    private function save(Request $request, ?int $id): void
    {
        $repository = new BookRepository();
        $existing = $id !== null ? $repository->find($id) : null;

        try {
            $cover = ImageUploadHelper::handle('cover', $existing['cover'] ?? null, 'books');
        } catch (\RuntimeException $e) {
            Flash::set('book_error', $e->getMessage());
            Response::redirect($id !== null ? '/admin/livros/' . $id . '/editar' : '/admin/livros/novo');
        }

        $data = [
            'title' => trim((string) $request->input('title', '')),
            'author' => trim((string) $request->input('author', '')),
            'description' => trim((string) $request->input('description', '')),
            'cover' => $cover,
            'link' => trim((string) $request->input('link', '')),
            'price' => trim((string) $request->input('price', '')),
            'currency' => trim((string) $request->input('currency', '')),
            'format' => trim((string) $request->input('format', '')),
            'sort_order' => (int) $request->input('sort_order', 0),
            'status' => $request->input('status', 'active'),
        ];

        if ($id === null) {
            $newId = $repository->create($data);
            (new AuditService())->log(Auth::id(), 'book_created', $request->ip(), 'book', (string) $newId);
        } else {
            $repository->update($id, $data);
            (new AuditService())->log(Auth::id(), 'book_updated', $request->ip(), 'book', (string) $id);
        }

        Flash::set('book_success', 'Livro salvo com sucesso.');
        Response::redirect('/admin/livros');
    }
}
