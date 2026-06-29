CREATE TABLE IF NOT EXISTS donation_methods (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    country_scope ENUM('national', 'international') NOT NULL,
    method_type ENUM('bank', 'pix', 'wise', 'western_union', 'zelle', 'other') NOT NULL DEFAULT 'bank',
    label VARCHAR(150) NOT NULL,
    details TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    INDEX idx_donation_methods_scope (country_scope, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
