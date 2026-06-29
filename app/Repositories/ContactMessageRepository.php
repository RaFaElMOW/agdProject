<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ContactMessageRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM contact_messages ORDER BY created_at DESC')->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO contact_messages (name, email, subject_option, message, ip, status, created_at)
             VALUES (:name, :email, :subject_option, :message, :ip, \'new\', NOW())'
        );
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'subject_option' => $data['subject_option'] ?: null,
            'message' => $data['message'],
            'ip' => $data['ip'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function setStatus(int $id, string $status): void
    {
        $this->db->prepare('UPDATE contact_messages SET status = :status WHERE id = :id')
            ->execute(['status' => $status, 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM contact_messages WHERE id = :id')->execute(['id' => $id]);
    }
}
