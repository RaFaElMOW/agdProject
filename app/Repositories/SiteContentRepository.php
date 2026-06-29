<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class SiteContentRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function get(string $key): ?array
    {
        $stmt = $this->db->prepare('SELECT data FROM site_content WHERE content_key = :key');
        $stmt->execute(['key' => $key]);
        $row = $stmt->fetch();
        return $row ? json_decode($row['data'], true) : null;
    }

    public function set(string $key, array $data): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO site_content (content_key, data, updated_at) VALUES (:key, :data, NOW())
             ON DUPLICATE KEY UPDATE data = VALUES(data), updated_at = NOW()'
        );
        $stmt->execute(['key' => $key, 'data' => json_encode($data, JSON_UNESCAPED_UNICODE)]);
    }
}
