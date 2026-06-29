<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM users ORDER BY name')->fetchAll();
    }

    public function updateProfile(int $userId, string $name, string $email, string $status): void
    {
        $this->db->prepare(
            'UPDATE users SET name = :name, email = :email, status = :status, updated_at = NOW() WHERE id = :id'
        )->execute(['name' => $name, 'email' => $email, 'status' => $status, 'id' => $userId]);
    }

    public function setStatus(int $userId, string $status): void
    {
        $this->db->prepare('UPDATE users SET status = :status, updated_at = NOW() WHERE id = :id')
            ->execute(['status' => $status, 'id' => $userId]);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, password_hash, status, must_change_password, created_by, created_at, updated_at)
             VALUES (:name, :email, :password_hash, :status, :must_change_password, :created_by, NOW(), NOW())'
        );
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
            'status' => $data['status'] ?? 'active',
            'must_change_password' => $data['must_change_password'] ?? 1,
            'created_by' => $data['created_by'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function incrementFailedAttempts(int $userId): void
    {
        $this->db->prepare(
            'UPDATE users SET failed_attempts = failed_attempts + 1 WHERE id = :id'
        )->execute(['id' => $userId]);
    }

    public function resetFailedAttempts(int $userId): void
    {
        $this->db->prepare(
            'UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = :id'
        )->execute(['id' => $userId]);
    }

    public function lockUntil(int $userId, string $datetime): void
    {
        $this->db->prepare(
            'UPDATE users SET locked_until = :locked_until WHERE id = :id'
        )->execute(['id' => $userId, 'locked_until' => $datetime]);
    }

    public function touchLastLogin(int $userId): void
    {
        $this->db->prepare(
            'UPDATE users SET last_login_at = NOW() WHERE id = :id'
        )->execute(['id' => $userId]);
    }

    public function bumpTokenVersion(int $userId): void
    {
        $this->db->prepare(
            'UPDATE users SET token_version = token_version + 1 WHERE id = :id'
        )->execute(['id' => $userId]);
    }

    public function updatePassword(int $userId, string $passwordHash, bool $mustChangePassword = false): void
    {
        $this->db->prepare(
            'UPDATE users SET password_hash = :hash, must_change_password = :must_change WHERE id = :id'
        )->execute([
            'hash' => $passwordHash,
            'must_change' => $mustChangePassword ? 1 : 0,
            'id' => $userId,
        ]);
    }

    public function permissionSlugsFor(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT DISTINCT p.slug
             FROM permissions p
             INNER JOIN role_permissions rp ON rp.permission_id = p.id
             INNER JOIN user_roles ur ON ur.role_id = rp.role_id
             WHERE ur.user_id = :user_id'
        );
        $stmt->execute(['user_id' => $userId]);
        return array_column($stmt->fetchAll(), 'slug');
    }
}
