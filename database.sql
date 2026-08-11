-- =================================================================
-- The Royal Palace of Benin — Database Schema
-- Import this file via phpMyAdmin or:
--   mysql -u USERNAME -p DATABASE_NAME < database.sql
-- =================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------
-- Table: admins
-- -----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(60)  NOT NULL,
    email         VARCHAR(190) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_admins_username (username),
    UNIQUE KEY uq_admins_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------
-- Table: news
-- -----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS news (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title          VARCHAR(255) NOT NULL,
    slug           VARCHAR(255) NOT NULL,
    excerpt        VARCHAR(500) DEFAULT NULL,
    content        MEDIUMTEXT NOT NULL,
    featured_image VARCHAR(255) DEFAULT NULL,
    category       VARCHAR(100) DEFAULT NULL,
    author         VARCHAR(150) DEFAULT NULL,
    status         ENUM('draft','published') NOT NULL DEFAULT 'draft',
    published_at   DATETIME DEFAULT NULL,
    created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_news_slug (slug),
    KEY idx_news_status_published (status, published_at),
    KEY idx_news_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------
-- Table: gallery
-- -----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS gallery (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) DEFAULT NULL,
    description VARCHAR(500) DEFAULT NULL,
    image       VARCHAR(255) NOT NULL,
    category    VARCHAR(100) DEFAULT NULL,
    status      ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_gallery_status (status),
    KEY idx_gallery_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------
-- Table: messages (contact + RSVP form submissions)
-- -----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS messages (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(150) NOT NULL,
    email      VARCHAR(190) NOT NULL,
    phone      VARCHAR(40)  DEFAULT NULL,
    subject    VARCHAR(200) DEFAULT NULL,
    message    TEXT NOT NULL,
    status     ENUM('unread','read') NOT NULL DEFAULT 'unread',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_messages_status (status),
    KEY idx_messages_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------------------
-- Default admin account
-- -----------------------------------------------------------------
-- No real password is hard-coded here. After importing this file,
-- create your first admin user by running, from the project root:
--
--   php admin/create-admin.php your_username your_email you@example.com your_password
--
-- (see admin/create-admin.php for the interactive alternative). That
-- script hashes the password with password_hash() before inserting it.
