<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class PaypalAccountRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM paypal_accounts ORDER BY country_scope ASC, sort_order ASC, id ASC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM paypal_accounts WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function activeFindFirstByScope(string $scope): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM paypal_accounts WHERE active = 1 AND country_scope = :scope ORDER BY sort_order ASC, id ASC LIMIT 1'
        );
        $stmt->execute(['scope' => $scope]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO paypal_accounts (label, currency, paypal_business_id, country_scope, sort_order, active, created_at, updated_at)
             VALUES (:label, :currency, :paypal_business_id, :country_scope, :sort_order, :active, NOW(), NOW())'
        );
        $stmt->execute($this->bind($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $params = $this->bind($data);
        $params['id'] = $id;
        $this->db->prepare(
            'UPDATE paypal_accounts SET label = :label, currency = :currency, paypal_business_id = :paypal_business_id,
             country_scope = :country_scope, sort_order = :sort_order, active = :active, updated_at = NOW() WHERE id = :id'
        )->execute($params);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM paypal_accounts WHERE id = :id')->execute(['id' => $id]);
    }

    private function bind(array $data): array
    {
        return [
            'label' => $data['label'],
            'currency' => $data['currency'] ?: 'USD',
            'paypal_business_id' => $data['paypal_business_id'],
            'country_scope' => $data['country_scope'] === 'international' ? 'international' : 'national',
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'active' => !empty($data['active']) ? 1 : 0,
        ];
    }
}
