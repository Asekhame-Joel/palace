<?php
/**
 * Shared helper functions.
 */
require_once __DIR__ . '/config.php';

/** Escape a string for safe HTML output. */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Redirect to a relative/absolute URL and stop execution. */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/** Build a URL-friendly slug from a title, guaranteed unique in a table. */
function make_unique_slug(string $title, string $table, ?int $ignoreId = null): string
{
    $base = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($title)), '-');
    $base = $base !== '' ? $base : 'post';
    $slug = $base;
    $i = 1;

    $sql = "SELECT COUNT(*) FROM {$table} WHERE slug = :slug" . ($ignoreId ? ' AND id != :id' : '');
    $stmt = db()->prepare($sql);

    while (true) {
        $params = ['slug' => $slug];
        if ($ignoreId) {
            $params['id'] = $ignoreId;
        }
        $stmt->execute($params);
        if ((int) $stmt->fetchColumn() === 0) {
            return $slug;
        }
        $i++;
        $slug = $base . '-' . $i;
    }
}

/** Format a date for public display, e.g. "17 July 2026". */
function format_date(?string $datetime): string
{
    if (!$datetime) {
        return '';
    }
    $ts = strtotime($datetime);
    return $ts ? date('j F Y', $ts) : '';
}

/** Truncate plain text to a given word count with an ellipsis. */
function excerpt_from_text(string $text, int $words = 30): string
{
    $text = trim(strip_tags($text));
    $parts = preg_split('/\s+/', $text);
    if (count($parts) <= $words) {
        return $text;
    }
    return implode(' ', array_slice($parts, 0, $words)) . '…';
}

/* ---------------------------------------------------------------
 * Shared session (used for both admin login state and public CSRF)
 * ------------------------------------------------------------- */

function ensure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function csrf_token(): string
{
    ensure_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): bool
{
    ensure_session();
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/* ---------------------------------------------------------------
 * Flash messages (session-based, one-time display banners)
 * ------------------------------------------------------------- */

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flash_all(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

/* ---------------------------------------------------------------
 * Secure image upload handling
 * ------------------------------------------------------------- */

/**
 * Validate and move an uploaded image into the given target directory.
 * Returns the new stored filename on success, or throws RuntimeException.
 */
function handle_image_upload(array $file, string $targetDir): string
{
    if (!isset($file['error']) || is_array($file['error'])) {
        throw new RuntimeException('Invalid upload parameters.');
    }

    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file was uploaded.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('The uploaded file is too large.');
        default:
            throw new RuntimeException('Upload failed. Please try again.');
    }

    if ($file['size'] > MAX_UPLOAD_BYTES) {
        throw new RuntimeException('The uploaded file exceeds the 5MB limit.');
    }

    // Verify it's genuinely an image (not just a renamed executable) using getimagesize.
    $imageInfo = @getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        throw new RuntimeException('The uploaded file is not a valid image.');
    }

    // Confirm real MIME type via fileinfo (server-side, not client-supplied).
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!in_array($mime, ALLOWED_IMAGE_MIME, true)) {
        throw new RuntimeException('Unsupported image type. Allowed: JPG, PNG, WEBP, GIF.');
    }

    $extMap = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];
    $ext = $extMap[$mime];

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Generate a random, safe filename — never trust the client-supplied name.
    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $destination = rtrim($targetDir, '/') . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Could not save the uploaded file.');
    }

    chmod($destination, 0644);

    return $filename;
}

/** Delete an uploaded file if it exists, ignoring errors. */
function delete_upload(string $dir, ?string $filename): void
{
    if (!$filename) {
        return;
    }
    $path = rtrim($dir, '/') . '/' . basename($filename);
    if (is_file($path)) {
        @unlink($path);
    }
}
