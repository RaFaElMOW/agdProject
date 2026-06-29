<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class MenuRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function forLocation(string $location): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM menus WHERE location = :location ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute(['location' => $location]);
        return $stmt->fetchAll();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM menus ORDER BY location ASC, sort_order ASC, id ASC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM menus WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO menus (location, label, url, parent_id, sort_order, target_blank, created_at, updated_at)
             VALUES (:location, :label, :url, :parent_id, :sort_order, :target_blank, NOW(), NOW())'
        );
        $stmt->execute([
            'location' => $data['location'],
            'label' => $data['label'],
            'url' => $data['url'],
            'parent_id' => $data['parent_id'] ?: null,
            'sort_order' => $data['sort_order'] ?? 0,
            'target_blank' => !empty($data['target_blank']) ? 1 : 0,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE menus SET location = :location, label = :label, url = :url, parent_id = :parent_id,
             sort_order = :sort_order, target_blank = :target_blank, updated_at = NOW() WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'location' => $data['location'],
            'label' => $data['label'],
            'url' => $data['url'],
            'parent_id' => $data['parent_id'] ?: null,
            'sort_order' => $data['sort_order'] ?? 0,
            'target_blank' => !empty($data['target_blank']) ? 1 : 0,
        ]);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM menus WHERE id = :id')->execute(['id' => $id]);
    }
}
