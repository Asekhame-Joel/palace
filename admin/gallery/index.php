<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    csrf_verify_or_die();
    $id = (int) $_POST['id'];
    $stmt = db()->prepare('SELECT status FROM gallery WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    if ($row) {
        $newStatus = $row['status'] === 'active' ? 'inactive' : 'active';
        db()->prepare('UPDATE gallery SET status = :s WHERE id = :id')->execute(['s' => $newStatus, 'id' => $id]);
        flash_set('success', 'Image status updated.');
    }
    redirect('/admin/gallery/index.php');
}

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

$total = (int) db()->query('SELECT COUNT(*) FROM gallery')->fetchColumn();
$totalPages = max(1, (int) ceil($total / $perPage));

$stmt = db()->prepare('SELECT id, title, image, category, status, created_at FROM gallery ORDER BY created_at DESC LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$images = $stmt->fetchAll();

$adminPageTitle = 'All Images';
$activeAdminNav = 'gallery';
require __DIR__ . '/../../includes/admin_header.php';
?>
        <div style="display:flex;justify-content:flex-end;margin-bottom:1rem">
          <a class="a-btn" href="/admin/gallery/upload.php">+ Upload Images</a>
        </div>
        <div class="a-card">
<?php if (empty($images)): ?>
          <p class="a-empty">No gallery images yet. <a href="/admin/gallery/upload.php">Upload your first image</a>.</p>
<?php else: ?>
          <table class="a-table">
            <thead><tr><th></th><th>Title</th><th>Category</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
<?php foreach ($images as $img): ?>
              <tr>
                <td><img class="thumb" src="/<?php echo e(UPLOADS_GALLERY_URL . '/' . $img['image']); ?>" alt=""></td>
                <td><?php echo e($img['title'] ?: '—'); ?></td>
                <td><?php echo e($img['category'] ?: '—'); ?></td>
                <td><span class="a-badge <?php echo e($img['status']); ?>"><?php echo e(ucfirst($img['status'])); ?></span></td>
                <td><?php echo e(format_date($img['created_at'])); ?></td>
                <td class="actions">
                  <a class="a-btn outline small" href="/admin/gallery/edit.php?id=<?php echo (int) $img['id']; ?>">Edit</a>
                  <form method="post" action="/admin/gallery/index.php" style="display:inline">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="toggle_status" value="1">
                    <input type="hidden" name="id" value="<?php echo (int) $img['id']; ?>">
                    <button class="a-btn outline small" type="submit"><?php echo $img['status'] === 'active' ? 'Deactivate' : 'Activate'; ?></button>
                  </form>
                  <form method="post" action="/admin/gallery/delete.php" style="display:inline" onsubmit="return confirm('Delete this image? This cannot be undone.');">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo (int) $img['id']; ?>">
                    <button class="a-btn danger small" type="submit">Delete</button>
                  </form>
                </td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
<?php if ($totalPages > 1): ?>
          <div class="a-pagination">
<?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="a-btn outline small" href="/admin/gallery/index.php?page=<?php echo $i; ?>" style="<?php echo $i === $page ? 'background:#f0f1f3' : ''; ?>"><?php echo $i; ?></a>
<?php endfor; ?>
          </div>
<?php endif; ?>
<?php endif; ?>
        </div>
<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
