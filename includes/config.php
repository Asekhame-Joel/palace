<?php
/**
 * Site configuration.
 * Copy this file's values from config.sample or set real credentials
 * before deploying. Never commit real production credentials.
 */

// ---- Database ----
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'palace_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// ---- Site ----
define('SITE_NAME', 'The Royal Palace of Benin');
define('SITE_URL', getenv('SITE_URL') ?: 'http://localhost');

// ---- Paths ----
define('BASE_PATH', dirname(__DIR__));
define('UPLOADS_NEWS_PATH', BASE_PATH . '/uploads/news');
define('UPLOADS_GALLERY_PATH', BASE_PATH . '/uploads/gallery');
define('UPLOADS_NEWS_URL', 'uploads/news');
define('UPLOADS_GALLERY_URL', 'uploads/gallery');

// ---- Uploads ----
define('MAX_UPLOAD_BYTES', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_MIME', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_IMAGE_EXT', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// ---- Session ----
define('SESSION_NAME', 'palace_admin_sid');
define('SESSION_IDLE_TIMEOUT', 60 * 30); // 30 minutes

// ---- Environment ----
// Set to false on production to hide detailed PHP errors.
define('APP_DEBUG', getenv('APP_DEBUG') === '1');

if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

date_default_timezone_set('Africa/Lagos');
