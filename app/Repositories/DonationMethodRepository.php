<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class DonationMethodRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM donation_methods ORDER BY country_scope ASC, sort_order ASC, id ASC')->fetchAll();
    }

    public function activeByScope(string $scope): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM donation_methods WHERE active = 1 AND country_scope = :scope ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute(['scope' => $scope]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM donation_methods WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO donation_methods (country_scope, method_type, label, details, sort_order, active, created_at, updated_at)
             VALUES (:country_scope, :method_type, :label, :details, :sort_order, :active, NOW(), NOW())'
        );
        $stmt->execute($this->bind($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $params = $this->bind($data);
        $params['id'] = $id;
        $this->db->prepare(
            'UPDATE donation_methods SET country_scope = :country_scope, method_type = :method_type, label = :label,
             details = :details, sort_order = :sort_order, active = :active, updated_at = NOW() WHERE id = :id'
        )->execute($params);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM donation_methods WHERE id = :id')->execute(['id' => $id]);
    }

    private function bind(array $data): array
    {
        return [
            'country_scope' => $data['country_scope'] === 'international' ? 'international' : 'national',
            'method_type' => in_array($data['method_type'], ['bank', 'pix', 'wise', 'western_union', 'zelle', 'other'], true) ? $data['method_type'] : 'other',
            'label' => $data['label'],
            'details' => $data['details'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'active' => !empty($data['active']) ? 1 : 0,
        ];
    }
}
