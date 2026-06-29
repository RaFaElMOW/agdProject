<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class TranslationRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    /**
     * @return array<string, string> translation_key => value, for one language only.
     */
    public function overridesFor(string $langCode): array
    {
        $stmt = $this->db->prepare('SELECT translation_key, value FROM translations WHERE lang_code = :lang');
        $stmt->execute(['lang' => $langCode]);

        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['translation_key']] = $row['value'];
        }
        return $result;
    }

    public function setOverride(string $langCode, string $key, string $value): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO translations (lang_code, translation_key, value, updated_at) VALUES (:lang, :key, :value, NOW())
             ON DUPLICATE KEY UPDATE value = VALUES(value), updated_at = NOW()'
        );
        $stmt->execute(['lang' => $langCode, 'key' => $key, 'value' => $value]);
    }

    public function clearOverride(string $langCode, string $key): void
    {
        $this->db->prepare('DELETE FROM translations WHERE lang_code = :lang AND translation_key = :key')
            ->execute(['lang' => $langCode, 'key' => $key]);
    }
}
