<?php
/**
 * Shared page header.
 *
 * Expected variables (set before including this file):
 *   $pageTitle       string  full <title> text
 *   $pageDescription string  meta description / og:description
 *   $ogImage         string  relative path to og:image (default hero-palace.jpg)
 *   $activeNav       string  one of: home, about, heritage, council, gallery, news, contact, anniversary
 *   $useImageLogo    bool    use the anniversary-logo image brand mark (homepage only) - optional
 */
$pageTitle       = $pageTitle ?? SITE_NAME;
$pageDescription = $pageDescription ?? 'The Royal Palace of Benin — living heritage of the Benin Kingdom, Edo State, Nigeria.';
$ogImage         = $ogImage ?? 'assets/images/hero-palace.jpg';
$activeNav       = $activeNav ?? '';
$useImageLogo    = $useImageLogo ?? false;

function nav_current(string $key, string $active): string
{
    return $key === $active ? ' aria-current="page"' : '';
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo e($pageTitle); ?></title>
<meta name="description" content="<?php echo e($pageDescription); ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?php echo e($pageTitle); ?>">
<meta property="og:description" content="<?php echo e($pageDescription); ?>">
<meta property="og:image" content="<?php echo e($ogImage); ?>">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" type="image/png" href="favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/palace.css">
</head>
<body>
  <header class="nav nav--float">
    <div class="shell nav__inner">
      <a class="brand" href="index.php" aria-label="The Royal Palace of Benin — home">
<?php if ($useImageLogo): ?>
        <span class="brand__mark">
          <img src="assets/images/anniversary-logo.jpg" alt="" width="40" height="40" />
        </span>
<?php else: ?>
        <span class="brand__mark" aria-hidden="true">B</span>
<?php endif; ?>
        <span class="brand__text"><b>Royal Palace</b><span>of Benin</span></span>
      </a>
      <nav class="nav__links" aria-label="Primary">
          <a href="index.php"<?php echo nav_current('home', $activeNav); ?>>Home</a>
          <a href="about.php"<?php echo nav_current('about', $activeNav); ?>>About</a>
          <a href="heritage.php"<?php echo nav_current('heritage', $activeNav); ?>>Heritage</a>
          <a href="council.php"<?php echo nav_current('council', $activeNav); ?>>Council</a>
          <a href="gallery.php"<?php echo nav_current('gallery', $activeNav); ?>>Gallery</a>
          <a href="news.php"<?php echo nav_current('news', $activeNav); ?>>News</a>
          <a href="contact.php"<?php echo nav_current('contact', $activeNav); ?>>Contact</a>
          <a class="nav__cta" href="anniversary.php">10th Anniversary</a>
      </nav>
      <button class="burger" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="drawer">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>
  <div class="drawer" id="drawer">
      <a href="index.php"<?php echo nav_current('home', $activeNav); ?>>Home</a>
      <a href="about.php"<?php echo nav_current('about', $activeNav); ?>>About</a>
      <a href="heritage.php"<?php echo nav_current('heritage', $activeNav); ?>>Heritage</a>
      <a href="council.php"<?php echo nav_current('council', $activeNav); ?>>Council</a>
      <a href="gallery.php"<?php echo nav_current('gallery', $activeNav); ?>>Gallery</a>
      <a href="news.php"<?php echo nav_current('news', $activeNav); ?>>News</a>
      <a href="contact.php"<?php echo nav_current('contact', $activeNav); ?>>Contact</a>
      <a href="anniversary.php">10th Anniversary</a>
      <a class="btn btn--gold" href="anniversary.php">Explore the Anniversary</a>
  </div>
  <main id="main">
