CREATE TABLE IF NOT EXISTS translations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lang_code VARCHAR(10) NOT NULL,
    translation_key VARCHAR(191) NOT NULL,
    value TEXT NOT NULL,
    updated_at DATETIME NOT NULL,
    UNIQUE KEY uniq_translations_lang_key (lang_code, translation_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
