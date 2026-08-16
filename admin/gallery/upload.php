<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$errors = [];
$values = ['title' => '', 'description' => '', 'category' => ''];
$uploadedCount = 0;

$categories = db()->query("SELECT DISTINCT category FROM gallery WHERE category IS NOT NULL AND category != '' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();

    $values['title']       = trim((string) ($_POST['title'] ?? ''));
    $values['description'] = trim((string) ($_POST['description'] ?? ''));
    $values['category']    = trim((string) ($_POST['category'] ?? ''));

    $files = $_FILES['images'] ?? null;

    if (!$files || empty($files['name'][0])) {
        $errors[] = 'Please choose at least one image to upload.';
    } else {
        $count = count($files['name']);
        $insertStmt = db()->prepare('INSERT INTO gallery (title, description, image, category, status, created_at)
                                      VALUES (:title, :description, :image, :category, "active", NOW())');

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $file = [
                'name'     => $files['name'][$i],
                'type'     => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error'    => $files['error'][$i],
                'size'     => $files['size'][$i],
            ];
            try {
                $stored = handle_image_upload($file, UPLOADS_GALLERY_PATH);
                $title = $values['title'] !== '' && $count === 1
                    ? $values['title']
                    : ($values['title'] !== '' ? $values['title'] . ' ' . ($i + 1) : null);
                $insertStmt->execute([
                    'title'       => $title,
                    'description' => $values['description'] !== '' ? $values['description'] : null,
                    'image'       => $stored,
                    'category'    => $values['category'] !== '' ? $values['category'] : null,
                ]);
                $uploadedCount++;
            } catch (RuntimeException $ex) {
                $errors[] = ($files['name'][$i] ?? 'File') . ': ' . $ex->getMessage();
            }
        }
    }

    if ($uploadedCount > 0 && empty($errors)) {
        flash_set('success', $uploadedCount . ' image(s) uploaded successfully.');
        redirect('/admin/gallery/index.php');
    } elseif ($uploadedCount > 0) {
        flash_set('success', $uploadedCount . ' image(s) uploaded, but some files failed (see below).');
    }
}

$adminPageTitle = 'Upload Images';
$activeAdminNav = 'gallery-upload';
require __DIR__ . '/../../includes/admin_header.php';
?>
        <div class="a-card">
<?php foreach ($errors as $err): ?>
          <div class="a-alert error"><?php echo e($err); ?></div>
<?php endforeach; ?>
          <form method="post" action="/admin/gallery/upload.php" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="a-field">
              <label for="images">Images</label>
              <input type="file" id="images" name="images[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple required>
              <p class="hint">You can select multiple files at once. Each must be JPG, PNG, WEBP, or GIF, up to 5MB.</p>
            </div>
            <div class="a-form-grid">
              <div class="a-field">
                <label for="title">Title <span style="font-weight:400;color:var(--a-ink-60)">(optional)</span></label>
                <input type="text" id="title" name="title" value="<?php echo e($values['title']); ?>">
                <p class="hint">If uploading several images, this will be numbered automatically. You can rename each afterwards.</p>
              </div>
              <div class="a-field">
                <label for="category">Category <span style="font-weight:400;color:var(--a-ink-60)">(optional)</span></label>
                <input type="text" id="category" name="category" value="<?php echo e($values['category']); ?>" list="category-list" placeholder="e.g. Regalia, Ceremonies, Anniversary">
                <datalist id="category-list">
<?php foreach ($categories as $cat): ?>
                  <option value="<?php echo e($cat); ?>">
<?php endforeach; ?>
                </datalist>
              </div>
            </div>
            <div class="a-field">
              <label for="description">Description <span style="font-weight:400;color:var(--a-ink-60)">(optional, applied to all files in this batch)</span></label>
              <textarea id="description" name="description" rows="3"><?php echo e($values['description']); ?></textarea>
            </div>
            <button class="a-btn" type="submit">Upload</button>
            <a class="a-btn outline" href="/admin/gallery/index.php">Cancel</a>
          </form>
        </div>
<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
