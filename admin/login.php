<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

start_secure_session();

if (is_logged_in()) {
    redirect('/admin/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Your session expired. Please try again.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $error = 'Please enter both your username and password.';
        } elseif (attempt_login($username, $password)) {
            redirect('/admin/dashboard.php');
        } else {
            $error = 'Incorrect username or password.';
        }
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login — The Royal Palace of Benin</title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" type="image/png" href="/favicon.png">
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin">
  <div class="admin-login">
    <div class="admin-login__card">
      <div class="admin-login__brand">
        <div class="mark">B</div>
        <h1>Royal Palace of Benin</h1>
        <span>Admin Dashboard</span>
      </div>
<?php if ($error): ?>
      <div class="a-alert error"><?php echo e($error); ?></div>
<?php endif; ?>
      <form method="post" action="/admin/login.php">
        <?php echo csrf_field(); ?>
        <div class="a-field">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required autofocus autocomplete="username">
        </div>
        <div class="a-field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>
        <button class="a-btn" type="submit">Sign In</button>
      </form>
    </div>
  </div>
</body>
</html>
