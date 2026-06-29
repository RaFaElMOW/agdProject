CREATE TABLE IF NOT EXISTS rate_limit_hits (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rate_key VARCHAR(191) NOT NULL,
    window_start DATETIME NOT NULL,
    hit_count INT UNSIGNED NOT NULL DEFAULT 0,
    UNIQUE KEY uniq_rate_limit_key_window (rate_key, window_start)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
