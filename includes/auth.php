<?php
/**
 * Session-based admin authentication + CSRF protection.
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

function start_secure_session(): void
{
    ensure_session();

    // Idle timeout
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_IDLE_TIMEOUT) {
        $_SESSION = [];
        session_destroy();
        session_start();
    }
    $_SESSION['last_activity'] = time();
}

function is_logged_in(): bool
{
    return !empty($_SESSION['admin_id']);
}

/** Redirect to login if not authenticated. Call at the top of every protected admin page. */
function require_login(): void
{
    start_secure_session();
    if (!is_logged_in()) {
        redirect('/admin/login.php');
    }
}

function attempt_login(string $username, string $password): bool
{
    $stmt = db()->prepare('SELECT id, username, password_hash FROM admins WHERE username = :u LIMIT 1');
    $stmt->execute(['u' => $username]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        // Slow down brute-force attempts slightly.
        usleep(300000);
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['admin_id']       = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['last_activity']  = time();

    return true;
}

function logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

/* ---------------------------------------------------------------
 * CSRF protection - csrf_token()/csrf_field()/csrf_verify() live in
 * functions.php so public-facing pages can use them without pulling
 * in the full admin auth module.
 * ------------------------------------------------------------- */

function csrf_verify_or_die(): void
{
    if (!csrf_verify()) {
        http_response_code(403);
        die('Invalid or expired form submission. Please go back and try again.');
    }
}
