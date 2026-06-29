<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class BlogCommentRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function approvedForPost(int $postId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM blog_comments WHERE post_id = :post_id AND status = 'approved' ORDER BY created_at ASC"
        );
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchAll();
    }

    public function countApprovedForPost(int $postId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM blog_comments WHERE post_id = :post_id AND status = 'approved'");
        $stmt->execute(['post_id' => $postId]);
        return (int) $stmt->fetchColumn();
    }

    public function allWithPostTitle(): array
    {
        return $this->db->query(
            'SELECT c.*, p.title AS post_title, p.slug AS post_slug FROM blog_comments c
             INNER JOIN blog_posts p ON p.id = c.post_id ORDER BY c.created_at DESC'
        )->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM blog_comments WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO blog_comments (post_id, parent_id, author_name, author_email, content, status, ip, created_at)
             VALUES (:post_id, :parent_id, :author_name, :author_email, :content, :status, :ip, NOW())'
        );
        $stmt->execute([
            'post_id' => $data['post_id'],
            'parent_id' => $data['parent_id'] ?: null,
            'author_name' => $data['author_name'],
            'author_email' => $data['author_email'],
            'content' => $data['content'],
            'status' => 'pending',
            'ip' => $data['ip'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function setStatus(int $id, string $status): void
    {
        $this->db->prepare('UPDATE blog_comments SET status = :status WHERE id = :id')
            ->execute(['status' => $status, 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM blog_comments WHERE id = :id')->execute(['id' => $id]);
    }
}
