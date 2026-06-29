<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class TeamMemberRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM team_members ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public function activeOrdered(): array
    {
        return $this->db->query("SELECT * FROM team_members WHERE active = 1 ORDER BY sort_order ASC, id ASC")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM team_members WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO team_members (name, role, photo, bio, facebook, instagram, twitter, linkedin, sort_order, active, created_at, updated_at)
             VALUES (:name, :role, :photo, :bio, :facebook, :instagram, :twitter, :linkedin, :sort_order, :active, NOW(), NOW())'
        );
        $stmt->execute($this->bind($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $params = $this->bind($data);
        $params['id'] = $id;
        $this->db->prepare(
            'UPDATE team_members SET name = :name, role = :role, photo = :photo, bio = :bio, facebook = :facebook,
             instagram = :instagram, twitter = :twitter, linkedin = :linkedin, sort_order = :sort_order,
             active = :active, updated_at = NOW() WHERE id = :id'
        )->execute($params);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM team_members WHERE id = :id')->execute(['id' => $id]);
    }

    private function bind(array $data): array
    {
        return [
            'name' => $data['name'],
            'role' => $data['role'] ?: null,
            'photo' => $data['photo'] ?: null,
            'bio' => $data['bio'] ?: null,
            'facebook' => $data['facebook'] ?: null,
            'instagram' => $data['instagram'] ?: null,
            'twitter' => $data['twitter'] ?: null,
            'linkedin' => $data['linkedin'] ?: null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'active' => !empty($data['active']) ? 1 : 0,
        ];
    }
}
