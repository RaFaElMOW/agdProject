<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class BlogPostRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        return $this->db->query(
            'SELECT p.*, c.name AS category_name FROM blog_posts p
             LEFT JOIN blog_categories c ON c.id = p.category_id
             ORDER BY p.created_at DESC'
        )->fetchAll();
    }

    public function publishedPaginated(int $page, int $perPage = 9): array
    {
        $offset = (max(1, $page) - 1) * $perPage;
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name FROM blog_posts p
             LEFT JOIN blog_categories c ON c.id = p.category_id
             WHERE p.status IN ('published', 'scheduled') AND p.published_at <= NOW()
             ORDER BY p.published_at DESC LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $total = (int) $this->db->query(
            "SELECT COUNT(*) FROM blog_posts WHERE status IN ('published', 'scheduled') AND published_at <= NOW()"
        )->fetchColumn();

        return ['rows' => $rows, 'total' => $total];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM blog_posts WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findPublishedBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name FROM blog_posts p
             LEFT JOIN blog_categories c ON c.id = p.category_id
             WHERE p.slug = :slug AND p.status IN ('published', 'scheduled') AND p.published_at <= NOW()"
        );
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public function recentPublished(int $limit, ?int $excludePostId = null): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM blog_posts WHERE status IN ('published', 'scheduled') AND published_at <= NOW()
             AND id != :exclude_id ORDER BY published_at DESC LIMIT :limit"
        );
        $stmt->bindValue('exclude_id', $excludePostId ?? 0, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM blog_posts WHERE slug = :slug AND id != :exclude_id');
        $stmt->execute(['slug' => $slug, 'exclude_id' => $excludeId ?? 0]);
        return (bool) $stmt->fetchColumn();
    }

    public function create(array $data, array $tagIds): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO blog_posts (title, slug, excerpt, content, banner, author_id, category_id, status, published_at,
             meta_title, meta_description, og_image, created_at, updated_at)
             VALUES (:title, :slug, :excerpt, :content, :banner, :author_id, :category_id, :status, :published_at,
             :meta_title, :meta_description, :og_image, NOW(), NOW())'
        );
        $stmt->execute($this->bind($data));
        $id = (int) $this->db->lastInsertId();
        $this->syncTags($id, $tagIds);
        return $id;
    }

    public function update(int $id, array $data, array $tagIds): void
    {
        $params = $this->bind($data);
        $params['id'] = $id;
        $this->db->prepare(
            'UPDATE blog_posts SET title = :title, slug = :slug, excerpt = :excerpt, content = :content, banner = :banner,
             author_id = :author_id, category_id = :category_id, status = :status, published_at = :published_at,
             meta_title = :meta_title, meta_description = :meta_description, og_image = :og_image, updated_at = NOW()
             WHERE id = :id'
        )->execute($params);
        $this->syncTags($id, $tagIds);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM blog_posts WHERE id = :id')->execute(['id' => $id]);
    }

    private function syncTags(int $postId, array $tagIds): void
    {
        $this->db->prepare('DELETE FROM blog_post_tags WHERE post_id = :post_id')->execute(['post_id' => $postId]);
        $stmt = $this->db->prepare('INSERT INTO blog_post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)');
        foreach ($tagIds as $tagId) {
            $stmt->execute(['post_id' => $postId, 'tag_id' => $tagId]);
        }
    }

    private function bind(array $data): array
    {
        $status = in_array($data['status'], ['draft', 'scheduled', 'published'], true) ? $data['status'] : 'draft';
        $publishedAt = $data['published_at'] ?: null;

        if ($status === 'published' && $publishedAt === null) {
            $publishedAt = date('Y-m-d H:i:s');
        }

        return [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'] ?: null,
            'content' => $data['content'] ?: null,
            'banner' => $data['banner'] ?: null,
            'author_id' => $data['author_id'] ?: null,
            'category_id' => $data['category_id'] ?: null,
            'status' => $status,
            'published_at' => $publishedAt,
            'meta_title' => $data['meta_title'] ?: null,
            'meta_description' => $data['meta_description'] ?: null,
            'og_image' => $data['og_image'] ?: null,
        ];
    }
}
