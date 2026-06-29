<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ProjectRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM projects ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public function activeOrdered(): array
    {
        return $this->db->query("SELECT * FROM projects WHERE status != 'completed' ORDER BY sort_order ASC, id ASC")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM projects WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM projects WHERE slug = :slug');
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM projects WHERE slug = :slug AND id != :exclude_id');
        $stmt->execute(['slug' => $slug, 'exclude_id' => $excludeId ?? 0]);
        return (bool) $stmt->fetchColumn();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO projects (name, slug, description, banner, status, external_link, sort_order, created_at, updated_at)
             VALUES (:name, :slug, :description, :banner, :status, :external_link, :sort_order, NOW(), NOW())'
        );
        $stmt->execute($this->bind($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $params = $this->bind($data);
        $params['id'] = $id;
        $this->db->prepare(
            'UPDATE projects SET name = :name, slug = :slug, description = :description, banner = :banner,
             status = :status, external_link = :external_link, sort_order = :sort_order, updated_at = NOW() WHERE id = :id'
        )->execute($params);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM projects WHERE id = :id')->execute(['id' => $id]);
    }

    public function galleryFor(int $projectId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM project_gallery WHERE project_id = :id ORDER BY sort_order ASC, id ASC');
        $stmt->execute(['id' => $projectId]);
        return $stmt->fetchAll();
    }

    public function addGalleryImage(int $projectId, string $imagePath, int $sortOrder = 0): void
    {
        $this->db->prepare('INSERT INTO project_gallery (project_id, image_path, sort_order) VALUES (:project_id, :image_path, :sort_order)')
            ->execute(['project_id' => $projectId, 'image_path' => $imagePath, 'sort_order' => $sortOrder]);
    }

    public function deleteGalleryImage(int $imageId, int $projectId): void
    {
        $this->db->prepare('DELETE FROM project_gallery WHERE id = :id AND project_id = :project_id')
            ->execute(['id' => $imageId, 'project_id' => $projectId]);
    }

    private function bind(array $data): array
    {
        return [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?: null,
            'banner' => $data['banner'] ?: null,
            'status' => in_array($data['status'], ['active', 'paused', 'completed'], true) ? $data['status'] : 'active',
            'external_link' => $data['external_link'] ?: null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ];
    }
}
