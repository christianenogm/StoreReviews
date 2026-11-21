CREATE TABLE IF NOT EXISTS stores (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(30) NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS entries (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    review TEXT NOT NULL,
    date_entered DATETIME NOT NULL,
    storeId INT UNSIGNED NOT NULL,
    INDEX idx_entries_store (storeId),
    CONSTRAINT fk_entries_store
        FOREIGN KEY (storeId) REFERENCES stores(id)
        ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

INSERT INTO stores (location)
SELECT 'Westminster Mall'
WHERE NOT EXISTS (SELECT 1 FROM stores WHERE location = 'Westminster Mall');

INSERT INTO stores (location)
SELECT 'CF Masonville Place'
WHERE NOT EXISTS (SELECT 1 FROM stores WHERE location = 'CF Masonville Place');

INSERT INTO stores (location)
SELECT 'White Oaks Mall'
WHERE NOT EXISTS (SELECT 1 FROM stores WHERE location = 'White Oaks Mall');
