<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class BlogTagRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM blog_tags ORDER BY name ASC')->fetchAll();
    }

    public function findOrCreateByNames(array $names): array
    {
        $ids = [];
        $insert = $this->db->prepare('INSERT INTO blog_tags (name, slug, created_at) VALUES (:name, :slug, NOW())');
        $select = $this->db->prepare('SELECT id FROM blog_tags WHERE slug = :slug');

        foreach ($names as $name) {
            $name = trim($name);
            if ($name === '') {
                continue;
            }
            $slug = \App\Support\Slug::make($name);
            $select->execute(['slug' => $slug]);
            $id = $select->fetchColumn();
            if (!$id) {
                $insert->execute(['name' => $name, 'slug' => $slug]);
                $id = (int) $this->db->lastInsertId();
            }
            $ids[] = (int) $id;
        }

        return $ids;
    }

    public function namesForPost(int $postId): array
    {
        $stmt = $this->db->prepare(
            'SELECT t.name FROM blog_tags t INNER JOIN blog_post_tags pt ON pt.tag_id = t.id WHERE pt.post_id = :post_id ORDER BY t.name'
        );
        $stmt->execute(['post_id' => $postId]);
        return array_column($stmt->fetchAll(), 'name');
    }
}
