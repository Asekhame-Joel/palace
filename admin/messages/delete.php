<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/messages/index.php');
}
csrf_verify_or_die();

$id = (int) ($_POST['id'] ?? 0);
if ($id > 0) {
    $stmt = db()->prepare('DELETE FROM messages WHERE id = :id');
    $stmt->execute(['id' => $id]);
    flash_set('success', 'Message deleted.');
}

redirect('/admin/messages/index.php');
