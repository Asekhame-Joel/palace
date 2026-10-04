<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/news/index.php');
}
csrf_verify_or_die();

$id = (int) ($_POST['id'] ?? 0);
if ($id > 0) {
    $stmt = db()->prepare('SELECT featured_image FROM news WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    if ($row) {
        $imagesStmt = db()->prepare('SELECT image FROM news_images WHERE news_id = :id');
        $imagesStmt->execute(['id' => $id]);
        $attachedImages = $imagesStmt->fetchAll(PDO::FETCH_COLUMN);
        db()->prepare('DELETE FROM news WHERE id = :id')->execute(['id' => $id]);
        delete_upload(UPLOADS_NEWS_PATH, $row['featured_image']);
        foreach ($attachedImages as $image) {
            if ($image !== $row['featured_image']) {
                delete_upload(UPLOADS_NEWS_PATH, $image);
            }
        }
        flash_set('success', 'News post deleted.');
    } else {
        flash_set('error', 'News post not found.');
    }
}

redirect('/admin/news/index.php');
