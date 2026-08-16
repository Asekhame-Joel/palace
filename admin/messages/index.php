<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

// Toggle read/unread
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    csrf_verify_or_die();
    $id = (int) $_POST['id'];
    $stmt = db()->prepare('SELECT status FROM messages WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    if ($row) {
        $newStatus = $row['status'] === 'unread' ? 'read' : 'unread';
        db()->prepare('UPDATE messages SET status = :s WHERE id = :id')->execute(['s' => $newStatus, 'id' => $id]);
    }
    redirect('/admin/messages/index.php');
}

// View a single message (auto-marks as read)
$viewing = null;
if (isset($_GET['view'])) {
    $viewId = (int) $_GET['view'];
    $stmt = db()->prepare('SELECT * FROM messages WHERE id = :id');
    $stmt->execute(['id' => $viewId]);
    $viewing = $stmt->fetch();
    if ($viewing && $viewing['status'] === 'unread') {
        db()->prepare('UPDATE messages SET status = "read" WHERE id = :id')->execute(['id' => $viewId]);
        $viewing['status'] = 'read';
    }
}

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

$total = (int) db()->query('SELECT COUNT(*) FROM messages')->fetchColumn();
$totalPages = max(1, (int) ceil($total / $perPage));

$stmt = db()->prepare('SELECT id, name, email, subject, status, created_at FROM messages ORDER BY created_at DESC LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$messages = $stmt->fetchAll();

$adminPageTitle = 'Messages';
$activeAdminNav = 'messages';
require __DIR__ . '/../../includes/admin_header.php';
?>
<?php if ($viewing): ?>
        <div class="a-card">
          <h2><?php echo e($viewing['subject'] ?: 'General Enquiry'); ?></h2>
          <p class="hint" style="margin-bottom:1rem">
            From <strong><?php echo e($viewing['name']); ?></strong> &lt;<?php echo e($viewing['email']); ?>&gt;
<?php if (!empty($viewing['phone'])): ?>
            &middot; <?php echo e($viewing['phone']); ?>
<?php endif; ?>
            &middot; <?php echo e(format_date($viewing['created_at'])); ?>
          </p>
          <p style="white-space:pre-wrap;line-height:1.7"><?php echo e($viewing['message']); ?></p>
          <div style="margin-top:1.4rem;display:flex;gap:.6rem">
            <a class="a-btn outline" href="mailto:<?php echo e($viewing['email']); ?>">Reply by Email</a>
            <form method="post" action="/admin/messages/delete.php" onsubmit="return confirm('Delete this message? This cannot be undone.');">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="id" value="<?php echo (int) $viewing['id']; ?>">
              <button class="a-btn danger" type="submit">Delete</button>
            </form>
            <a class="a-btn outline" href="/admin/messages/index.php">Back to All Messages</a>
          </div>
        </div>
<?php endif; ?>
        <div class="a-card">
<?php if (empty($messages)): ?>
          <p class="a-empty">No messages yet.</p>
<?php else: ?>
          <table class="a-table">
            <thead><tr><th>From</th><th>Subject</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
<?php foreach ($messages as $m): ?>
              <tr>
                <td><?php echo e($m['name']); ?><br><span class="hint"><?php echo e($m['email']); ?></span></td>
                <td><?php echo e($m['subject'] ?: '—'); ?></td>
                <td><span class="a-badge <?php echo e($m['status']); ?>"><?php echo e(ucfirst($m['status'])); ?></span></td>
                <td><?php echo e(format_date($m['created_at'])); ?></td>
                <td class="actions">
                  <a class="a-btn outline small" href="/admin/messages/index.php?view=<?php echo (int) $m['id']; ?>">View</a>
                  <form method="post" action="/admin/messages/index.php" style="display:inline">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="toggle_status" value="1">
                    <input type="hidden" name="id" value="<?php echo (int) $m['id']; ?>">
                    <button class="a-btn outline small" type="submit"><?php echo $m['status'] === 'unread' ? 'Mark Read' : 'Mark Unread'; ?></button>
                  </form>
                  <form method="post" action="/admin/messages/delete.php" style="display:inline" onsubmit="return confirm('Delete this message? This cannot be undone.');">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo (int) $m['id']; ?>">
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
            <a class="a-btn outline small" href="/admin/messages/index.php?page=<?php echo $i; ?>" style="<?php echo $i === $page ? 'background:#f0f1f3' : ''; ?>"><?php echo $i; ?></a>
<?php endfor; ?>
          </div>
<?php endif; ?>
<?php endif; ?>
        </div>
<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
