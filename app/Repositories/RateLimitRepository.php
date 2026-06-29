<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * Fixed-window counter backed by MySQL — no Redis required, works on shared hosting.
 */
class RateLimitRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function hit(string $key, int $windowStartTimestamp): int
    {
        $windowStart = date('Y-m-d H:i:s', $windowStartTimestamp);

        $stmt = $this->db->prepare(
            'INSERT INTO rate_limit_hits (rate_key, window_start, hit_count)
             VALUES (:key, :window_start, 1)
             ON DUPLICATE KEY UPDATE hit_count = hit_count + 1'
        );
        $stmt->execute(['key' => $key, 'window_start' => $windowStart]);

        $select = $this->db->prepare(
            'SELECT hit_count FROM rate_limit_hits WHERE rate_key = :key AND window_start = :window_start'
        );
        $select->execute(['key' => $key, 'window_start' => $windowStart]);
        return (int) ($select->fetchColumn() ?: 0);
    }

    public function purgeOlderThan(int $timestamp): void
    {
        $this->db->prepare('DELETE FROM rate_limit_hits WHERE window_start < :ts')
            ->execute(['ts' => date('Y-m-d H:i:s', $timestamp)]);
    }
}
