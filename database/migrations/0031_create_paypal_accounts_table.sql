CREATE TABLE IF NOT EXISTS paypal_accounts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(150) NOT NULL,
    currency VARCHAR(3) NOT NULL DEFAULT 'USD',
    paypal_business_id VARCHAR(190) NOT NULL,
    country_scope ENUM('national', 'international') NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    INDEX idx_paypal_accounts_scope (country_scope, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
