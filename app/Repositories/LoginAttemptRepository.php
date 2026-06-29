<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class LoginAttemptRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function record(string $identifier, bool $success, string $ip, string $userAgent): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO login_attempts (identifier, success, ip, user_agent, created_at)
             VALUES (:identifier, :success, :ip, :user_agent, NOW())'
        );
        $stmt->execute([
            'identifier' => $identifier,
            'success' => $success ? 1 : 0,
            'ip' => $ip,
            'user_agent' => $userAgent,
        ]);
    }
}
