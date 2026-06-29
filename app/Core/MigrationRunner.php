<?php

namespace App\Core;

use PDOException;

/**
 * Runs pending database/migrations/*.sql files in filename order, tracked in
 * `migrations_log` so re-running is a no-op. Built to be triggered over HTTP
 * (see tools/migrate.php) since the target shared host has no SSH access.
 *
 * Also tolerates a mixed deploy path: someone may have already imported some/all of
 * these .sql files by hand via phpMyAdmin (no `migrations_log` entries exist for them).
 * In that case `CREATE TABLE ...` is naturally a no-op (`IF NOT EXISTS`), but a plain
 * `ALTER TABLE ... ADD COLUMN` is NOT idempotent and would throw "duplicate column" —
 * this is treated as "already applied" (logged and skipped) instead of aborting the
 * whole batch, so the seed step that normally follows still runs.
 */
class MigrationRunner
{
    private const MIGRATIONS_PATH = __DIR__ . '/../../database/migrations';

    /** MySQL SQLSTATE codes meaning "the change this migration makes is already there". */
    private const ALREADY_APPLIED_SQLSTATES = [
        '42S01', // table already exists
        '42S21', // column already exists
        '42000', // some "duplicate" errors surface under the generic syntax/access SQLSTATE on MySQL — inspected further below
    ];

    public function run(): array
    {
        $db = Database::connection();
        $executed = [];
        $skippedAlreadyApplied = [];

        $files = glob(self::MIGRATIONS_PATH . '/*.sql');
        sort($files);

        foreach ($files as $file) {
            $name = basename($file);

            if ($this->alreadyExecuted($db, $name)) {
                continue;
            }

            $sql = file_get_contents($file);

            try {
                $db->exec($sql);
            } catch (PDOException $e) {
                if (!$this->isAlreadyAppliedError($e)) {
                    throw $e;
                }
                $skippedAlreadyApplied[] = $name;
            }

            $this->markExecuted($db, $name);
            $executed[] = $name;
        }

        return ['executed' => $executed, 'already_applied_manually' => $skippedAlreadyApplied];
    }

    private function isAlreadyAppliedError(PDOException $e): bool
    {
        $sqlState = (string) ($e->errorInfo[0] ?? $e->getCode());
        $driverMessage = (string) ($e->errorInfo[2] ?? $e->getMessage());

        if (in_array($sqlState, ['42S01', '42S21'], true)) {
            return true;
        }

        // MySQL sometimes reports duplicate-column/duplicate-key under SQLSTATE 42000 —
        // disambiguate by message instead of trusting the SQLSTATE alone.
        return (bool) preg_match('/already exists|duplicate column|duplicate key name/i', $driverMessage);
    }

    private function alreadyExecuted(\PDO $db, string $name): bool
    {
        try {
            $stmt = $db->prepare('SELECT 1 FROM migrations_log WHERE migration = :name');
            $stmt->execute(['name' => $name]);
            return (bool) $stmt->fetchColumn();
        } catch (PDOException) {
            return false;
        }
    }

    private function markExecuted(\PDO $db, string $name): void
    {
        $db->prepare('INSERT INTO migrations_log (migration, executed_at) VALUES (:name, NOW())')
            ->execute(['name' => $name]);
    }
}
