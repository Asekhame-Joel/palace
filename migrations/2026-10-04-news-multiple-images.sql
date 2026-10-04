-- Adds articles containing both text and multiple uploaded images.
ALTER TABLE news
    MODIFY COLUMN post_type ENUM('standard','text','image','mixed') NOT NULL DEFAULT 'standard';

CREATE TABLE IF NOT EXISTS news_images (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    news_id    INT UNSIGNED NOT NULL,
    image      VARCHAR(255) NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_news_images_news FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE,
    KEY idx_news_images_order (news_id, sort_order, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
