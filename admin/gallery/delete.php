<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/gallery/index.php');
}
csrf_verify_or_die();

$id = (int) ($_POST['id'] ?? 0);
if ($id > 0) {
    $stmt = db()->prepare('SELECT image FROM gallery WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    if ($row) {
        db()->prepare('DELETE FROM gallery WHERE id = :id')->execute(['id' => $id]);
        delete_upload(UPLOADS_GALLERY_PATH, $row['image']);
        flash_set('success', 'Image deleted.');
    } else {
        flash_set('error', 'Image not found.');
    }
}

redirect('/admin/gallery/index.php');
