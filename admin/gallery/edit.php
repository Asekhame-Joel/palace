<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id <= 0) {
    redirect('/admin/gallery/index.php');
}

$stmt = db()->prepare('SELECT * FROM gallery WHERE id = :id');
$stmt->execute(['id' => $id]);
$image = $stmt->fetch();

if (!$image) {
    flash_set('error', 'Image not found.');
    redirect('/admin/gallery/index.php');
}

$errors = [];
$values = [
    'title'       => $image['title'] ?? '',
    'description' => $image['description'] ?? '',
    'category'    => $image['category'] ?? '',
    'status'      => $image['status'],
];

$categories = db()->query("SELECT DISTINCT category FROM gallery WHERE category IS NOT NULL AND category != '' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();

    $values['title']       = trim((string) ($_POST['title'] ?? ''));
    $values['description'] = trim((string) ($_POST['description'] ?? ''));
    $values['category']    = trim((string) ($_POST['category'] ?? ''));
    $values['status']      = ($_POST['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active';

    $newImage = null;
    if (!empty($_FILES['image']['name'])) {
        try {
            $newImage = handle_image_upload($_FILES['image'], UPLOADS_GALLERY_PATH);
        } catch (RuntimeException $ex) {
            $errors[] = $ex->getMessage();
        }
    }

    if (empty($errors)) {
        $finalImage = $image['image'];
        if ($newImage) {
            delete_upload(UPLOADS_GALLERY_PATH, $image['image']);
            $finalImage = $newImage;
        }

        $upd = db()->prepare('UPDATE gallery SET title=:title, description=:description, image=:image, category=:category, status=:status WHERE id=:id');
        $upd->execute([
            'title'       => $values['title'] !== '' ? $values['title'] : null,
            'description' => $values['description'] !== '' ? $values['description'] : null,
            'image'       => $finalImage,
            'category'    => $values['category'] !== '' ? $values['category'] : null,
            'status'      => $values['status'],
            'id'          => $id,
        ]);

        flash_set('success', 'Image updated successfully.');
        redirect('/admin/gallery/index.php');
    }
}

$adminPageTitle = 'Edit Image';
$activeAdminNav = 'gallery';
require __DIR__ . '/../../includes/admin_header.php';
?>
        <div class="a-card">
<?php foreach ($errors as $err): ?>
          <div class="a-alert error"><?php echo e($err); ?></div>
<?php endforeach; ?>
          <form method="post" action="/admin/gallery/edit.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="a-field">
              <label>Current Image</label>
              <div class="a-current-image">
                <img src="/<?php echo e(UPLOADS_GALLERY_URL . '/' . $image['image']); ?>" alt="">
              </div>
              <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
              <p class="hint">Upload a new image to replace the current one. Max 5MB.</p>
            </div>
            <div class="a-form-grid">
              <div class="a-field">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo e($values['title']); ?>">
              </div>
              <div class="a-field">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" value="<?php echo e($values['category']); ?>" list="category-list">
                <datalist id="category-list">
<?php foreach ($categories as $cat): ?>
                  <option value="<?php echo e($cat); ?>">
<?php endforeach; ?>
                </datalist>
              </div>
              <div class="a-field">
                <label for="status">Status</label>
                <select id="status" name="status">
                  <option value="active" <?php echo $values['status'] === 'active' ? 'selected' : ''; ?>>Active (visible on site)</option>
                  <option value="inactive" <?php echo $values['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive (hidden)</option>
                </select>
              </div>
            </div>
            <div class="a-field">
              <label for="description">Description</label>
              <textarea id="description" name="description" rows="3"><?php echo e($values['description']); ?></textarea>
            </div>
            <button class="a-btn" type="submit">Save Changes</button>
            <a class="a-btn outline" href="/admin/gallery/index.php">Cancel</a>
          </form>
        </div>
<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
