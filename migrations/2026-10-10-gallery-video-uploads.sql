-- Extend gallery videos to support locally uploaded videos alongside YouTube links.
-- Run once on an existing Palace database before deploying the corresponding PHP files.
ALTER TABLE gallery_videos
    ADD COLUMN video_type ENUM('youtube','upload') NOT NULL DEFAULT 'youtube' AFTER title,
    ADD COLUMN description TEXT NULL AFTER video_type,
    MODIFY COLUMN youtube_id VARCHAR(11) NULL,
    MODIFY COLUMN youtube_url VARCHAR(500) NULL,
    ADD COLUMN video_file VARCHAR(255) NULL AFTER youtube_url,
    ADD COLUMN thumbnail VARCHAR(255) NULL AFTER video_file;
