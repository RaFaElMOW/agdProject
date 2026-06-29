<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class BookRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM books ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public function activeOrdered(): array
    {
        return $this->db->query("SELECT * FROM books WHERE status = 'active' ORDER BY sort_order ASC, id ASC")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM books WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO books (title, author, description, cover, link, price, currency, format, sort_order, status, created_at, updated_at)
             VALUES (:title, :author, :description, :cover, :link, :price, :currency, :format, :sort_order, :status, NOW(), NOW())'
        );
        $stmt->execute($this->bind($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $params = $this->bind($data);
        $params['id'] = $id;
        $this->db->prepare(
            'UPDATE books SET title = :title, author = :author, description = :description, cover = :cover, link = :link,
             price = :price, currency = :currency, format = :format, sort_order = :sort_order, status = :status, updated_at = NOW() WHERE id = :id'
        )->execute($params);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM books WHERE id = :id')->execute(['id' => $id]);
    }

    private function bind(array $data): array
    {
        return [
            'title' => $data['title'],
            'author' => $data['author'] ?: null,
            'description' => $data['description'] ?: null,
            'cover' => $data['cover'] ?: null,
            'link' => $data['link'] ?: null,
            'price' => $data['price'] !== '' ? $data['price'] : null,
            'currency' => $data['currency'] ?: null,
            'format' => $data['format'] ?: null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'status' => $data['status'] === 'inactive' ? 'inactive' : 'active',
        ];
    }
}
