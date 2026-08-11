<?php
/**
 * Command-line utility to create an admin account.
 *
 * Usage (run from the project root or anywhere via full path):
 *   php admin/create-admin.php <username> <email> <password>
 *
 * Example:
 *   php admin/create-admin.php palaceadmin admin@obabenin.ng "A very strong password"
 *
 * This must be run from the command line (SSH / terminal), not the browser.
 * It is intentionally blocked from running as a web request.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    die("This script can only be run from the command line.\n");
}

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$args = $argv;
array_shift($args); // script name

if (count($args) < 3) {
    fwrite(STDERR, "Usage: php admin/create-admin.php <username> <email> <password>\n");
    exit(1);
}

[$username, $email, $password] = $args;

if (mb_strlen($username) < 3) {
    fwrite(STDERR, "Username must be at least 3 characters.\n");
    exit(1);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fwrite(STDERR, "Please provide a valid email address.\n");
    exit(1);
}
if (mb_strlen($password) < 8) {
    fwrite(STDERR, "Password must be at least 8 characters.\n");
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = db()->prepare('INSERT INTO admins (username, email, password_hash) VALUES (:u, :e, :p)');
    $stmt->execute(['u' => $username, 'e' => $email, 'p' => $hash]);
    echo "Admin account '{$username}' created successfully.\n";
} catch (PDOException $ex) {
    if ($ex->getCode() === '23000') {
        fwrite(STDERR, "An admin with that username or email already exists.\n");
    } else {
        fwrite(STDERR, "Could not create admin: " . $ex->getMessage() . "\n");
    }
    exit(1);
}
