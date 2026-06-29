<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class MediaItemRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM media_items ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public function activeByType(string $type): array
    {
        $stmt = $this->db->prepare("SELECT * FROM media_items WHERE active = 1 AND type = :type ORDER BY sort_order ASC, id ASC");
        $stmt->execute(['type' => $type]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM media_items WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO media_items (type, title, url_or_path, thumbnail, category, sort_order, active, created_at, updated_at)
             VALUES (:type, :title, :url_or_path, :thumbnail, :category, :sort_order, :active, NOW(), NOW())'
        );
        $stmt->execute($this->bind($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $params = $this->bind($data);
        $params['id'] = $id;
        $this->db->prepare(
            'UPDATE media_items SET type = :type, title = :title, url_or_path = :url_or_path, thumbnail = :thumbnail,
             category = :category, sort_order = :sort_order, active = :active, updated_at = NOW() WHERE id = :id'
        )->execute($params);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM media_items WHERE id = :id')->execute(['id' => $id]);
    }

    private function bind(array $data): array
    {
        return [
            'type' => $data['type'] === 'video' ? 'video' : 'image',
            'title' => $data['title'] ?: null,
            'url_or_path' => $data['url_or_path'],
            'thumbnail' => $data['thumbnail'] ?: null,
            'category' => $data['category'] ?: null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'active' => !empty($data['active']) ? 1 : 0,
        ];
    }
}
