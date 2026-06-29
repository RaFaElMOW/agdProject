CREATE TABLE IF NOT EXISTS media_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type ENUM('image', 'video') NOT NULL,
    title VARCHAR(200) NULL,
    url_or_path VARCHAR(500) NOT NULL,
    thumbnail VARCHAR(500) NULL,
    category VARCHAR(80) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
