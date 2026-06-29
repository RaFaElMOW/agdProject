<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class BlogCategoryRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM blog_categories ORDER BY name ASC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM blog_categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name, string $slug): int
    {
        $this->db->prepare('INSERT INTO blog_categories (name, slug, created_at) VALUES (:name, :slug, NOW())')
            ->execute(['name' => $name, 'slug' => $slug]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $name, string $slug): void
    {
        $this->db->prepare('UPDATE blog_categories SET name = :name, slug = :slug WHERE id = :id')
            ->execute(['name' => $name, 'slug' => $slug, 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM blog_categories WHERE id = :id')->execute(['id' => $id]);
    }

    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM blog_categories WHERE slug = :slug AND id != :exclude_id');
        $stmt->execute(['slug' => $slug, 'exclude_id' => $excludeId ?? 0]);
        return (bool) $stmt->fetchColumn();
    }
}
