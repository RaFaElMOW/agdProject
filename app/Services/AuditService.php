<?php

namespace App\Services;

use App\Repositories\AuditLogRepository;

class AuditService
{
    private AuditLogRepository $repository;

    public function __construct(?AuditLogRepository $repository = null)
    {
        $this->repository = $repository ?? new AuditLogRepository();
    }

    public function log(?int $userId, string $action, string $ip, ?string $entityType = null, ?string $entityId = null, array $metadata = []): void
    {
        $this->repository->log($userId, $action, $entityType, $entityId, $ip, $metadata);
    }
}
