<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$totalNews     = (int) db()->query("SELECT COUNT(*) FROM news")->fetchColumn();
$publishedNews = (int) db()->query("SELECT COUNT(*) FROM news WHERE status = 'published'")->fetchColumn();
$totalGallery  = (int) db()->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
$unreadMsgs    = (int) db()->query("SELECT COUNT(*) FROM messages WHERE status = 'unread'")->fetchColumn();

$recentNews = db()->query("SELECT title, slug, status, published_at, created_at FROM news ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentMsgs = db()->query("SELECT id, name, subject, status, created_at FROM messages ORDER BY created_at DESC LIMIT 5")->fetchAll();

$adminPageTitle = 'Dashboard';
$activeAdminNav = 'dashboard';
require __DIR__ . '/../includes/admin_header.php';
?>
        <div class="a-stats">
          <div class="a-stat"><b><?php echo $totalNews; ?></b><span>Total News Posts</span></div>
          <div class="a-stat"><b><?php echo $publishedNews; ?></b><span>Published News</span></div>
          <div class="a-stat"><b><?php echo $totalGallery; ?></b><span>Gallery Images</span></div>
          <div class="a-stat"><b><?php echo $unreadMsgs; ?></b><span>Unread Messages</span></div>
        </div>

        <div class="a-card">
          <h2>Recent News</h2>
<?php if (empty($recentNews)): ?>
          <p class="a-empty">No news posts yet. <a href="/admin/news/create.php">Add your first post</a>.</p>
<?php else: ?>
          <table class="a-table">
            <thead><tr><th>Title</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
<?php foreach ($recentNews as $n): ?>
              <tr>
                <td><?php echo e($n['title']); ?></td>
                <td><span class="a-badge <?php echo e($n['status']); ?>"><?php echo e(ucfirst($n['status'])); ?></span></td>
                <td><?php echo e(format_date($n['published_at'] ?? $n['created_at'])); ?></td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
<?php endif; ?>
        </div>

        <div class="a-card">
          <h2>Recent Messages</h2>
<?php if (empty($recentMsgs)): ?>
          <p class="a-empty">No messages yet.</p>
<?php else: ?>
          <table class="a-table">
            <thead><tr><th>From</th><th>Subject</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
<?php foreach ($recentMsgs as $m): ?>
              <tr>
                <td><?php echo e($m['name']); ?></td>
                <td><?php echo e($m['subject'] ?: '—'); ?></td>
                <td><span class="a-badge <?php echo e($m['status']); ?>"><?php echo e(ucfirst($m['status'])); ?></span></td>
                <td><?php echo e(format_date($m['created_at'])); ?></td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
<?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
