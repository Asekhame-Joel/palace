<?php
/**
 * Shared admin layout header.
 * Requires: require_login() already called by the including page.
 * Expected variables:
 *   $adminPageTitle string
 *   $activeAdminNav string one of: dashboard, news, news-add, gallery, gallery-upload, messages, settings
 *
 * NOTE: paths here are root-relative (/admin/..., /assets/...) which assumes
 * the site is deployed at the web root of its domain/subdomain — the normal
 * case for cPanel/Hostinger hosting. If deploying into a subfolder, either
 * move the project to the domain root or adjust these paths accordingly.
 */
$adminPageTitle = $adminPageTitle ?? 'Admin';
$activeAdminNav = $activeAdminNav ?? '';
$flashes = flash_all();

function admin_nav_class(string $key, string $active): string
{
    return $key === $active ? ' class="active"' : '';
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo e($adminPageTitle); ?> — Admin — The Royal Palace of Benin</title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" type="image/png" href="/favicon.png">
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin">
  <div class="admin-shell">
    <aside class="admin-sidebar">
      <div class="admin-sidebar__brand">
        <span class="mark">B</span>
        <span>
          <strong>Royal Palace</strong>
          <span>Admin Dashboard</span>
        </span>
      </div>
      <nav class="admin-nav">
        <a href="/admin/dashboard.php"<?php echo admin_nav_class('dashboard', $activeAdminNav); ?>>Dashboard</a>

        <div class="admin-nav-group">
          <div class="admin-nav-group__label">News</div>
          <a href="/admin/news/index.php"<?php echo admin_nav_class('news', $activeAdminNav); ?>>All News</a>
          <a href="/admin/news/create.php"<?php echo admin_nav_class('news-add', $activeAdminNav); ?>>Add News</a>
        </div>

        <div class="admin-nav-group">
          <div class="admin-nav-group__label">Gallery</div>
          <a href="/admin/gallery/index.php"<?php echo admin_nav_class('gallery', $activeAdminNav); ?>>All Images</a>
          <a href="/admin/gallery/upload.php"<?php echo admin_nav_class('gallery-upload', $activeAdminNav); ?>>Upload Images</a>
        </div>

        <div class="admin-nav-group">
          <div class="admin-nav-group__label">Enquiries</div>
          <a href="/admin/messages/index.php"<?php echo admin_nav_class('messages', $activeAdminNav); ?>>Messages</a>
        </div>

        <div class="admin-nav-group">
          <div class="admin-nav-group__label">Account</div>
          <a href="/admin/settings.php"<?php echo admin_nav_class('settings', $activeAdminNav); ?>>Settings</a>
          <a href="/admin/logout.php" class="logout">Logout</a>
        </div>
      </nav>
    </aside>

    <div class="admin-main">
      <div class="admin-topbar">
        <h1><?php echo e($adminPageTitle); ?></h1>
        <span class="who">Signed in as <strong><?php echo e($_SESSION['admin_username'] ?? ''); ?></strong></span>
      </div>
      <div class="admin-content">
<?php foreach ($flashes as $f): ?>
        <div class="a-alert <?php echo e($f['type']); ?>"><?php echo e($f['message']); ?></div>
<?php endforeach; ?>
