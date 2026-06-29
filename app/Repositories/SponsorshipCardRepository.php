<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class SponsorshipCardRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM sponsorship_cards ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public function activeOrdered(): array
    {
        return $this->db->query("SELECT * FROM sponsorship_cards WHERE status = 'active' ORDER BY sort_order ASC, id ASC")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM sponsorship_cards WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO sponsorship_cards (title, description, value, currency, image, icon, cta_link, sort_order, status, created_at, updated_at)
             VALUES (:title, :description, :value, :currency, :image, :icon, :cta_link, :sort_order, :status, NOW(), NOW())'
        );
        $stmt->execute($this->bind($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $params = $this->bind($data);
        $params['id'] = $id;
        $this->db->prepare(
            'UPDATE sponsorship_cards SET title = :title, description = :description, value = :value, currency = :currency,
             image = :image, icon = :icon, cta_link = :cta_link, sort_order = :sort_order, status = :status, updated_at = NOW() WHERE id = :id'
        )->execute($params);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM sponsorship_cards WHERE id = :id')->execute(['id' => $id]);
    }

    private function bind(array $data): array
    {
        return [
            'title' => $data['title'],
            'description' => $data['description'] ?: null,
            'value' => $data['value'] !== '' ? $data['value'] : null,
            'currency' => $data['currency'] ?: 'USD',
            'image' => $data['image'] ?: null,
            'icon' => $data['icon'] ?: null,
            'cta_link' => $data['cta_link'] ?: null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'status' => $data['status'] === 'inactive' ? 'inactive' : 'active',
        ];
    }
}
