<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$errors = [];
$values = ['title' => '', 'youtube_url' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();
    $action = (string) ($_POST['action'] ?? 'add');

    if ($action === 'toggle') {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = db()->prepare('SELECT status FROM gallery_videos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $video = $stmt->fetch();
        if ($video) {
            $status = $video['status'] === 'active' ? 'inactive' : 'active';
            db()->prepare('UPDATE gallery_videos SET status = :status WHERE id = :id')->execute(['status' => $status, 'id' => $id]);
            flash_set('success', 'Video status updated.');
        }
        redirect('/admin/gallery/videos.php');
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = db()->prepare('SELECT video_type, video_file, thumbnail FROM gallery_videos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $video = $stmt->fetch();
        db()->prepare('DELETE FROM gallery_videos WHERE id = :id')->execute(['id' => $id]);
        if ($video && $video['video_type'] === 'upload') {
            delete_upload(UPLOADS_VIDEO_PATH, $video['video_file']);
            delete_upload(UPLOADS_VIDEO_PATH, $video['thumbnail']);
        }
        flash_set('success', 'Video removed from the gallery.');
        redirect('/admin/gallery/videos.php');
    }

    $values['title'] = trim((string) ($_POST['title'] ?? ''));
    $values['youtube_url'] = trim((string) ($_POST['youtube_url'] ?? ''));
    $youtubeId = youtube_video_id($values['youtube_url']);

    if ($values['title'] === '' || mb_strlen($values['title']) > 255) {
        $errors[] = 'Please enter a video title (up to 255 characters).';
    }
    if (!$youtubeId) {
        $errors[] = 'Please enter a valid YouTube video link.';
    }

    if (empty($errors)) {
        try {
            $stmt = db()->prepare('INSERT INTO gallery_videos (title, video_type, youtube_id, youtube_url, status, created_at)
                                   VALUES (:title, "youtube", :youtube_id, :youtube_url, "active", NOW())');
            $stmt->execute([
                'title' => $values['title'],
                'youtube_id' => $youtubeId,
                'youtube_url' => 'https://www.youtube.com/watch?v=' . $youtubeId,
            ]);
            flash_set('success', 'YouTube video added to the gallery.');
            redirect('/admin/gallery/videos.php');
        } catch (PDOException $ex) {
            if ((string) $ex->getCode() === '23000') {
                $errors[] = 'This YouTube video is already in the gallery.';
            } else {
                throw $ex;
            }
        }
    }
}

$videos = db()->query('SELECT id, title, video_type, description, youtube_id, youtube_url, video_file, thumbnail, status, created_at
                       FROM gallery_videos ORDER BY created_at DESC')->fetchAll();

$adminPageTitle = 'All Videos';
$activeAdminNav = 'gallery-videos';
require __DIR__ . '/../../includes/admin_header.php';
?>
        <div class="a-page-head">
          <div>
            <h2>Gallery Videos</h2>
            <p class="hint">Manage uploaded Palace videos and linked YouTube videos in one place.</p>
          </div>
          <a class="a-btn" href="/admin/gallery/video-upload.php">+ Upload Video</a>
        </div>

        <div class="a-card" style="margin-bottom:1.5rem">
          <h2 style="margin-top:0">Add a YouTube Video</h2>
          <p class="hint">Paste the YouTube link. The thumbnail is created automatically; no video file is uploaded.</p>
<?php foreach ($errors as $err): ?>
          <div class="a-alert error"><?php echo e($err); ?></div>
<?php endforeach; ?>
          <form method="post" action="/admin/gallery/videos.php">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="add">
            <div class="a-form-grid">
              <div class="a-field">
                <label for="title">Video Title</label>
                <input type="text" id="title" name="title" maxlength="255" required value="<?php echo e($values['title']); ?>" placeholder="e.g. Coronation of the Oba of Benin">
              </div>
              <div class="a-field">
                <label for="youtube_url">YouTube Link</label>
                <input type="url" id="youtube_url" name="youtube_url" required value="<?php echo e($values['youtube_url']); ?>" placeholder="https://youtu.be/...">
              </div>
            </div>
            <button class="a-btn" type="submit">Add Video</button>
          </form>
        </div>

        <div class="a-card">
          <h2 style="margin-top:0">Published &amp; Hidden Videos</h2>
<?php if (empty($videos)): ?>
          <p class="a-empty">No videos have been added yet.</p>
<?php else: ?>
          <table class="a-table">
            <thead><tr><th>Thumbnail</th><th>Title</th><th>Type</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
<?php foreach ($videos as $video): ?>
              <tr>
                <td><img class="thumb" src="<?php echo $video['video_type'] === 'upload' ? '/' . e(UPLOADS_VIDEO_URL . '/' . $video['thumbnail']) : 'https://i.ytimg.com/vi/' . e($video['youtube_id']) . '/hqdefault.jpg'; ?>" alt=""></td>
                <td>
<?php if ($video['video_type'] === 'youtube'): ?>
                  <a href="<?php echo e($video['youtube_url']); ?>" target="_blank" rel="noopener noreferrer"><?php echo e($video['title']); ?></a>
<?php else: ?>
                  <strong><?php echo e($video['title']); ?></strong>
<?php if ($video['description']): ?><div class="hint"><?php echo e(excerpt_from_text($video['description'], 14)); ?></div><?php endif; ?>
<?php endif; ?>
                </td>
                <td><span class="a-badge"><?php echo $video['video_type'] === 'upload' ? 'Uploaded' : 'YouTube'; ?></span></td>
                <td><span class="a-badge <?php echo e($video['status']); ?>"><?php echo e(ucfirst($video['status'])); ?></span></td>
                <td><?php echo e(format_date($video['created_at'])); ?></td>
                <td class="actions">
                  <form method="post" action="/admin/gallery/videos.php" style="display:inline">
                    <?php echo csrf_field(); ?><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?php echo (int) $video['id']; ?>">
                    <button class="a-btn outline small" type="submit"><?php echo $video['status'] === 'active' ? 'Hide' : 'Show'; ?></button>
                  </form>
                  <form method="post" action="/admin/gallery/videos.php" style="display:inline" onsubmit="return confirm('Remove this video from the gallery?');">
                    <?php echo csrf_field(); ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo (int) $video['id']; ?>">
                    <button class="a-btn danger small" type="submit">Remove</button>
                  </form>
                </td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
<?php endif; ?>
        </div>
<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
