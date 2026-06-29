<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class AuditLogRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function log(?int $userId, string $action, ?string $entityType, ?string $entityId, string $ip, array $metadata = []): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO audit_logs (user_id, action, entity_type, entity_id, ip, metadata, created_at)
             VALUES (:user_id, :action, :entity_type, :entity_id, :ip, :metadata, NOW())'
        );
        $stmt->execute([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'ip' => $ip,
            'metadata' => json_encode($metadata, JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function recent(int $limit = 100): array
    {
        $stmt = $this->db->prepare('SELECT * FROM audit_logs ORDER BY id DESC LIMIT :limit');
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @return array{rows: array, total: int}
     */
    public function paginated(int $page, int $perPage = 50): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare(
            'SELECT al.*, u.name AS user_name FROM audit_logs al
             LEFT JOIN users u ON u.id = al.user_id
             ORDER BY al.id DESC LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $total = (int) $this->db->query('SELECT COUNT(*) FROM audit_logs')->fetchColumn();

        return ['rows' => $stmt->fetchAll(), 'total' => $total];
    }
}
