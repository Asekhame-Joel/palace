<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$errors = [];
$values = [
    'title' => '',
    'description' => '',
    'status' => 'active',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();

    $values['title'] = trim((string) ($_POST['title'] ?? ''));
    $values['description'] = trim((string) ($_POST['description'] ?? ''));
    $values['status'] = ($_POST['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active';

    if ($values['title'] === '' || mb_strlen($values['title']) > 255) {
        $errors[] = 'Please enter a video title (up to 255 characters).';
    }
    if (mb_strlen($values['description']) > 3000) {
        $errors[] = 'The description must be 3,000 characters or fewer.';
    }
    if (empty($_FILES['video']['name'])) {
        $errors[] = 'Please choose a video file.';
    }
    if (empty($_FILES['thumbnail']['name'])) {
        $errors[] = 'Please choose a thumbnail image.';
    }

    if (empty($errors)) {
        $storedVideo = null;
        $storedThumbnail = null;

        try {
            $storedVideo = handle_video_upload($_FILES['video'], UPLOADS_VIDEO_PATH);
            $storedThumbnail = handle_image_upload($_FILES['thumbnail'], UPLOADS_VIDEO_PATH);

            $stmt = db()->prepare('INSERT INTO gallery_videos
                (title, video_type, description, youtube_id, youtube_url, video_file, thumbnail, status, created_at)
                VALUES (:title, "upload", :description, NULL, NULL, :video_file, :thumbnail, :status, NOW())');
            $stmt->execute([
                'title' => $values['title'],
                'description' => $values['description'] !== '' ? $values['description'] : null,
                'video_file' => $storedVideo,
                'thumbnail' => $storedThumbnail,
                'status' => $values['status'],
            ]);

            flash_set('success', $values['status'] === 'active'
                ? 'Video uploaded and published successfully.'
                : 'Video uploaded and saved as hidden.');
            redirect('/admin/gallery/videos.php');
        } catch (RuntimeException $ex) {
            delete_upload(UPLOADS_VIDEO_PATH, $storedVideo);
            delete_upload(UPLOADS_VIDEO_PATH, $storedThumbnail);
            $errors[] = $ex->getMessage();
        } catch (Throwable $ex) {
            delete_upload(UPLOADS_VIDEO_PATH, $storedVideo);
            delete_upload(UPLOADS_VIDEO_PATH, $storedThumbnail);
            throw $ex;
        }
    }
}

$adminPageTitle = 'Upload Video';
$activeAdminNav = 'gallery-video-upload';
require __DIR__ . '/../../includes/admin_header.php';
?>
        <div class="a-card">
          <h2>Upload a Palace Video</h2>
          <p class="hint">Add a video file, its public title and caption, and a featured image shown before playback.</p>
<?php foreach ($errors as $err): ?>
          <div class="a-alert error"><?php echo e($err); ?></div>
<?php endforeach; ?>
          <form method="post" action="/admin/gallery/video-upload.php" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="a-field">
              <label for="title">Video Title</label>
              <input type="text" id="title" name="title" maxlength="255" required value="<?php echo e($values['title']); ?>" placeholder="e.g. Highlights from the Royal Palace">
            </div>
            <div class="a-field">
              <label for="description">Description / Caption</label>
              <textarea id="description" name="description" rows="5" maxlength="3000" placeholder="Describe the ceremony, occasion, or people featured in this video."><?php echo e($values['description']); ?></textarea>
            </div>
            <div class="a-form-grid">
              <div class="a-field">
                <label for="video">Video File</label>
                <input type="file" id="video" name="video" accept="video/mp4,video/webm,video/ogg" required>
                <p class="hint">MP4 is recommended for the widest browser support. Maximum file size: 150MB.</p>
              </div>
              <div class="a-field">
                <label for="thumbnail">Thumbnail / Featured Image</label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/webp,image/gif" required>
                <p class="hint">JPG, PNG, WEBP, or GIF up to 5MB. A 16:9 landscape image works best.</p>
              </div>
            </div>
            <div class="a-field">
              <label for="status">Publishing</label>
              <select id="status" name="status">
                <option value="active"<?php echo $values['status'] === 'active' ? ' selected' : ''; ?>>Publish immediately</option>
                <option value="inactive"<?php echo $values['status'] === 'inactive' ? ' selected' : ''; ?>>Keep hidden</option>
              </select>
            </div>
            <button class="a-btn" type="submit">Upload &amp; Save Video</button>
            <a class="a-btn outline" href="/admin/gallery/videos.php">Cancel</a>
          </form>
        </div>
<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
