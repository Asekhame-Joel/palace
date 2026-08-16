<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

// Handle quick publish/unpublish toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    csrf_verify_or_die();
    $id = (int) $_POST['id'];
    $stmt = db()->prepare('SELECT status, published_at FROM news WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    if ($row) {
        $newStatus = $row['status'] === 'published' ? 'draft' : 'published';
        $publishedAt = $row['published_at'];
        if ($newStatus === 'published' && !$publishedAt) {
            $publishedAt = date('Y-m-d H:i:s');
        }
        $upd = db()->prepare('UPDATE news SET status = :s, published_at = :p WHERE id = :id');
        $upd->execute(['s' => $newStatus, 'p' => $publishedAt, 'id' => $id]);
        flash_set('success', 'Post status updated.');
    }
    redirect('/admin/news/index.php');
}

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 15;
$offset = ($page - 1) * $perPage;

$total = (int) db()->query('SELECT COUNT(*) FROM news')->fetchColumn();
$totalPages = max(1, (int) ceil($total / $perPage));

$stmt = db()->prepare('SELECT id, title, slug, category, status, published_at, created_at FROM news ORDER BY created_at DESC LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

$adminPageTitle = 'All News';
$activeAdminNav = 'news';
require __DIR__ . '/../../includes/admin_header.php';
?>
        <div style="display:flex;justify-content:flex-end;margin-bottom:1rem">
          <a class="a-btn" href="/admin/news/create.php">+ Add News</a>
        </div>
        <div class="a-card">
<?php if (empty($posts)): ?>
          <p class="a-empty">No news posts yet. <a href="/admin/news/create.php">Add your first post</a>.</p>
<?php else: ?>
          <table class="a-table">
            <thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
<?php foreach ($posts as $p): ?>
              <tr>
                <td><?php echo e($p['title']); ?></td>
                <td><?php echo e($p['category'] ?: '—'); ?></td>
                <td><span class="a-badge <?php echo e($p['status']); ?>"><?php echo e(ucfirst($p['status'])); ?></span></td>
                <td><?php echo e(format_date($p['published_at'] ?? $p['created_at'])); ?></td>
                <td class="actions">
                  <a class="a-btn outline small" href="/admin/news/edit.php?id=<?php echo (int) $p['id']; ?>">Edit</a>
                  <form method="post" action="/admin/news/index.php" style="display:inline">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="toggle_status" value="1">
                    <input type="hidden" name="id" value="<?php echo (int) $p['id']; ?>">
                    <button class="a-btn outline small" type="submit"><?php echo $p['status'] === 'published' ? 'Unpublish' : 'Publish'; ?></button>
                  </form>
                  <form method="post" action="/admin/news/delete.php" style="display:inline" onsubmit="return confirm('Delete this post? This cannot be undone.');">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo (int) $p['id']; ?>">
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
            <a class="a-btn outline small" href="/admin/news/index.php?page=<?php echo $i; ?>" style="<?php echo $i === $page ? 'background:#f0f1f3' : ''; ?>"><?php echo $i; ?></a>
<?php endfor; ?>
          </div>
<?php endif; ?>
<?php endif; ?>
        </div>
<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
