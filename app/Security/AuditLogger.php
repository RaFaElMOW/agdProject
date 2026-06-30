<?php

declare(strict_types=1);

namespace App\Security;

use App\Repositories\AuditLogRepository;

/**
 * Thin wrapper around AuditLogRepository that:
 *  - respects the `audit_enabled` toggle from SecuritySettings;
 *  - enforces `log_retention_days` by purging old rows opportunistically.
 *
 * Controllers may continue to use AuditService directly for routine CRUD logging;
 * this class is used by the Security module itself (token regeneration, settings
 * changes, security-relevant events) and by the retention sweep.
 */
class AuditLogger
{
    private AuditLogRepository $repository;
    private SecuritySettings $settings;

    public function __construct(?AuditLogRepository $repository = null, ?SecuritySettings $settings = null)
    {
        $this->repository = $repository ?? new AuditLogRepository();
        $this->settings = $settings ?? SecuritySettings::getInstance();
    }

    public function log(?int $userId, string $action, string $ip, ?string $entityType = null, ?string $entityId = null, array $metadata = []): void
    {
        if (!$this->settings->getBool('audit_enabled')) {
            return;
        }
        $this->repository->log($userId, $action, $entityType, $entityId, $ip, $metadata);
    }

    /**
     * Deletes audit_logs rows older than log_retention_days.
     * 0 means "keep indefinitely" — no-op in that case.
     */
    public function purgeExpired(): int
    {
        $days = $this->settings->getInt('log_retention_days');
        if ($days <= 0) {
            return 0;
        }

        $db = \App\Core\Database::connection();
        $stmt = $db->prepare('DELETE FROM audit_logs WHERE created_at < (NOW() - INTERVAL :days DAY)');
        $stmt->bindValue('days', $days, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }
}
