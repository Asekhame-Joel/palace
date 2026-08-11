<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$stmt = db()->prepare('SELECT id, username, email FROM admins WHERE id = :id');
$stmt->execute(['id' => $_SESSION['admin_id']]);
$admin = $stmt->fetch();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();

    $email = trim((string) ($_POST['email'] ?? ''));
    $currentPassword = (string) ($_POST['current_password'] ?? '');
    $newPassword = (string) ($_POST['new_password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    $stmt = db()->prepare('SELECT password_hash FROM admins WHERE id = :id');
    $stmt->execute(['id' => $admin['id']]);
    $hash = $stmt->fetchColumn();

    if ($currentPassword === '' || !password_verify($currentPassword, $hash)) {
        $errors[] = 'Your current password is incorrect.';
    }

    if ($newPassword !== '') {
        if (mb_strlen($newPassword) < 8) {
            $errors[] = 'New password must be at least 8 characters.';
        } elseif ($newPassword !== $confirmPassword) {
            $errors[] = 'New password and confirmation do not match.';
        }
    }

    if (empty($errors)) {
        if ($newPassword !== '') {
            $upd = db()->prepare('UPDATE admins SET email = :email, password_hash = :hash, updated_at = NOW() WHERE id = :id');
            $upd->execute(['email' => $email, 'hash' => password_hash($newPassword, PASSWORD_DEFAULT), 'id' => $admin['id']]);
        } else {
            $upd = db()->prepare('UPDATE admins SET email = :email, updated_at = NOW() WHERE id = :id');
            $upd->execute(['email' => $email, 'id' => $admin['id']]);
        }
        flash_set('success', 'Settings updated successfully.');
        redirect('/admin/settings.php');
    }

    $admin['email'] = $email;
}

$adminPageTitle = 'Settings';
$activeAdminNav = 'settings';
require __DIR__ . '/../includes/admin_header.php';
?>
        <div class="a-card" style="max-width:520px">
<?php foreach ($errors as $err): ?>
          <div class="a-alert error"><?php echo e($err); ?></div>
<?php endforeach; ?>
          <form method="post" action="/admin/settings.php">
            <?php echo csrf_field(); ?>
            <div class="a-field">
              <label>Username</label>
              <input type="text" value="<?php echo e($admin['username']); ?>" disabled>
              <p class="hint">Usernames cannot be changed here. Contact your developer if needed.</p>
            </div>
            <div class="a-field">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" value="<?php echo e($admin['email']); ?>" required>
            </div>
            <div class="a-field">
              <label for="current_password">Current Password</label>
              <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
              <p class="hint">Required to confirm any changes.</p>
            </div>
            <div class="a-field">
              <label for="new_password">New Password <span style="font-weight:400;color:var(--a-ink-60)">(leave blank to keep current password)</span></label>
              <input type="password" id="new_password" name="new_password" autocomplete="new-password">
            </div>
            <div class="a-field">
              <label for="confirm_password">Confirm New Password</label>
              <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password">
            </div>
            <button class="a-btn" type="submit">Save Changes</button>
          </form>
        </div>
<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
