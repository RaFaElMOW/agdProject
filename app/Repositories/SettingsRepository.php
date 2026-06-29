<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class SettingsRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT setting_key, setting_value FROM settings');
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['setting_key']] = $row['setting_value'];
        }
        return $result;
    }

    public function set(string $key, ?string $value, string $group = 'general'): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO settings (setting_key, setting_value, setting_group, updated_at)
             VALUES (:key, :value, :group, NOW())
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), setting_group = VALUES(setting_group), updated_at = NOW()'
        );
        $stmt->execute(['key' => $key, 'value' => $value, 'group' => $group]);
    }

    /**
     * @param array<string, array{0: ?string, 1: string}> $values key => [value, group]
     */
    public function setMany(array $values): void
    {
        foreach ($values as $key => [$value, $group]) {
            $this->set($key, $value, $group);
        }
    }

    /**
     * Used by the seeder: inserts a default only if the key doesn't exist yet, so
     * re-running the seed (e.g. when a future phase adds new default settings) never
     * clobbers a value an admin has already customized through the panel.
     */
    public function setIfMissing(string $key, ?string $value, string $group = 'general'): void
    {
        $this->db->prepare('INSERT IGNORE INTO settings (setting_key, setting_value, setting_group, updated_at) VALUES (:key, :value, :group, NOW())')
            ->execute(['key' => $key, 'value' => $value, 'group' => $group]);
    }
}
