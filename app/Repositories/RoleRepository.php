<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class RoleRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM roles ORDER BY name')->fetchAll();
    }

    public function idsForUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT role_id FROM user_roles WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        return array_map('intval', array_column($stmt->fetchAll(), 'role_id'));
    }

    public function namesForUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT r.name FROM roles r INNER JOIN user_roles ur ON ur.role_id = r.id WHERE ur.user_id = :user_id ORDER BY r.name'
        );
        $stmt->execute(['user_id' => $userId]);
        return array_column($stmt->fetchAll(), 'name');
    }

    public function syncForUser(int $userId, array $roleIds): void
    {
        $this->db->prepare('DELETE FROM user_roles WHERE user_id = :user_id')->execute(['user_id' => $userId]);
        $stmt = $this->db->prepare('INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)');
        foreach ($roleIds as $roleId) {
            $stmt->execute(['user_id' => $userId, 'role_id' => (int) $roleId]);
        }
    }
}
