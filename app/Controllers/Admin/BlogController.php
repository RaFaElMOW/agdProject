<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use App\Repositories\BlogTagRepository;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;
use App\Support\ImageUploadHelper;
use App\Support\Sanitizer;
use App\Support\Slug;

class BlogController
{
    public function index(Request $request): void
    {
        View::output('admin/blog', [
            'posts' => (new BlogPostRepository())->all(),
            'success' => Flash::pull('blog_success'),
            'error' => Flash::pull('blog_error'),
        ], 'admin/layout');
    }

    public function create(Request $request): void
    {
        $this->renderForm(null);
    }

    public function store(Request $request): void
    {
        $this->save($request, null);
    }

    public function edit(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        $post = (new BlogPostRepository())->find($id);
        if ($post === null) {
            Response::notFound('Post não encontrado.');
        }
        $this->renderForm($post);
    }

    public function update(Request $request, array $params): void
    {
        $this->save($request, (int) $params['id']);
    }

    public function destroy(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new BlogPostRepository())->delete($id);
        (new AuditService())->log(Auth::id(), 'blog_post_deleted', $request->ip(), 'blog_post', (string) $id);
        Flash::set('blog_success', 'Post removido.');
        Response::redirect('/admin/blog');
    }

    public function categories(Request $request): void
    {
        View::output('admin/blog-categories', [
            'categories' => (new BlogCategoryRepository())->all(),
            'success' => Flash::pull('blog_category_success'),
            'error' => Flash::pull('blog_category_error'),
        ], 'admin/layout');
    }

    public function storeCategory(Request $request): void
    {
        $name = trim((string) $request->input('name', ''));
        if ($name === '') {
            Flash::set('blog_category_error', 'Informe um nome.');
            Response::redirect('/admin/blog/categorias');
        }

        $repository = new BlogCategoryRepository();
        $slug = Slug::make($name);
        if ($repository->slugExists($slug)) {
            $slug .= '-' . bin2hex(random_bytes(2));
        }

        $id = $repository->create($name, $slug);
        (new AuditService())->log(Auth::id(), 'blog_category_created', $request->ip(), 'blog_category', (string) $id);
        Flash::set('blog_category_success', 'Categoria criada.');
        Response::redirect('/admin/blog/categorias');
    }

    public function destroyCategory(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new BlogCategoryRepository())->delete($id);
        (new AuditService())->log(Auth::id(), 'blog_category_deleted', $request->ip(), 'blog_category', (string) $id);
        Flash::set('blog_category_success', 'Categoria removida.');
        Response::redirect('/admin/blog/categorias');
    }

    private function renderForm(?array $post): void
    {
        View::output('admin/blog-form', [
            'post' => $post,
            'categories' => (new BlogCategoryRepository())->all(),
            'tags' => $post ? implode(', ', (new BlogTagRepository())->namesForPost((int) $post['id'])) : '',
            'error' => Flash::pull('blog_error'),
        ], 'admin/layout');
    }

    private function save(Request $request, ?int $id): void
    {
        $repository = new BlogPostRepository();
        $existing = $id !== null ? $repository->find($id) : null;
        $redirectBack = $id !== null ? '/admin/blog/' . $id . '/editar' : '/admin/blog/novo';

        $title = trim((string) $request->input('title', ''));
        $slug = trim((string) $request->input('slug', '')) ?: Slug::make($title);
        $slug = Slug::make($slug);
        if ($repository->slugExists($slug, $id)) {
            $slug .= '-' . bin2hex(random_bytes(2));
        }

        try {
            $banner = ImageUploadHelper::handle('banner', $existing['banner'] ?? null, 'blog');
            $ogImage = ImageUploadHelper::handle('og_image', $existing['og_image'] ?? null, 'blog');
        } catch (\RuntimeException $e) {
            Flash::set('blog_error', $e->getMessage());
            Response::redirect($redirectBack);
        }

        $status = $request->input('status', 'draft');
        $publishedAtInput = trim((string) $request->input('published_at', ''));
        $publishedAt = $publishedAtInput !== '' ? str_replace('T', ' ', $publishedAtInput) . ':00' : null;

        if ($status === 'scheduled' && $publishedAt === null) {
            Flash::set('blog_error', 'Defina a data/hora de agendamento.');
            Response::redirect($redirectBack);
        }

        $data = [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => trim((string) $request->input('excerpt', '')),
            'content' => Sanitizer::richText((string) $request->input('content', '')),
            'banner' => $banner,
            'author_id' => Auth::id(),
            'category_id' => (int) $request->input('category_id', 0) ?: null,
            'status' => $status,
            'published_at' => $publishedAt,
            'meta_title' => trim((string) $request->input('meta_title', '')),
            'meta_description' => trim((string) $request->input('meta_description', '')),
            'og_image' => $ogImage,
        ];

        $tagNames = array_filter(array_map('trim', explode(',', (string) $request->input('tags', ''))));
        $tagIds = (new BlogTagRepository())->findOrCreateByNames($tagNames);

        if ($id === null) {
            $newId = $repository->create($data, $tagIds);
            (new AuditService())->log(Auth::id(), 'blog_post_created', $request->ip(), 'blog_post', (string) $newId);
        } else {
            $repository->update($id, $data, $tagIds);
            (new AuditService())->log(Auth::id(), 'blog_post_updated', $request->ip(), 'blog_post', (string) $id);
        }

        Flash::set('blog_success', 'Post salvo com sucesso.');
        Response::redirect('/admin/blog');
    }
}
