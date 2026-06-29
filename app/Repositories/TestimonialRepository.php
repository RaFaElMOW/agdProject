<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class TestimonialRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM testimonials ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public function activeOrdered(): array
    {
        return $this->db->query('SELECT * FROM testimonials WHERE active = 1 ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM testimonials WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO testimonials (name, role, photo, text, youtube_url, sort_order, active, created_at, updated_at)
             VALUES (:name, :role, :photo, :text, :youtube_url, :sort_order, :active, NOW(), NOW())'
        );
        $stmt->execute($this->bind($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $params = $this->bind($data);
        $params['id'] = $id;
        $this->db->prepare(
            'UPDATE testimonials SET name = :name, role = :role, photo = :photo, text = :text, youtube_url = :youtube_url,
             sort_order = :sort_order, active = :active, updated_at = NOW() WHERE id = :id'
        )->execute($params);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM testimonials WHERE id = :id')->execute(['id' => $id]);
    }

    private function bind(array $data): array
    {
        return [
            'name' => $data['name'],
            'role' => $data['role'] ?: null,
            'photo' => $data['photo'] ?: null,
            'text' => $data['text'],
            'youtube_url' => $data['youtube_url'] ?: null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'active' => !empty($data['active']) ? 1 : 0,
        ];
    }
}
