<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class DonationPresetAmountRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM donation_preset_amounts ORDER BY currency ASC, sort_order ASC, id ASC')->fetchAll();
    }

    public function activeByCurrency(string $currency): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM donation_preset_amounts WHERE active = 1 AND currency = :currency ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute(['currency' => $currency]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM donation_preset_amounts WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO donation_preset_amounts (currency, amount, sort_order, active, created_at) VALUES (:currency, :amount, :sort_order, :active, NOW())'
        );
        $stmt->execute($this->bind($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $params = $this->bind($data);
        $params['id'] = $id;
        $this->db->prepare(
            'UPDATE donation_preset_amounts SET currency = :currency, amount = :amount, sort_order = :sort_order, active = :active WHERE id = :id'
        )->execute($params);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM donation_preset_amounts WHERE id = :id')->execute(['id' => $id]);
    }

    private function bind(array $data): array
    {
        return [
            'currency' => $data['currency'],
            'amount' => $data['amount'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'active' => !empty($data['active']) ? 1 : 0,
        ];
    }
}
