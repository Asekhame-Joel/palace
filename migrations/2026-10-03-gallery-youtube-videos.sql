-- Adds linked YouTube videos to the public gallery. No video files are uploaded.
CREATE TABLE IF NOT EXISTS gallery_videos (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    youtube_id  VARCHAR(11) NOT NULL,
    youtube_url VARCHAR(500) NOT NULL,
    status      ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_gallery_videos_youtube_id (youtube_id),
    KEY idx_gallery_videos_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
