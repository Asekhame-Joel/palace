-- Adds explicit layouts for new text-only and image-only news posts.
-- Existing posts remain "standard" so their current image-and-text layout is unchanged.
ALTER TABLE news
    ADD COLUMN post_type ENUM('standard','text','image') NOT NULL DEFAULT 'standard'
    AFTER featured_image;
