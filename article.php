<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$slug = trim((string) ($_GET['slug'] ?? ''));

if ($slug === '') {
    redirect('news.php');
}

$stmt = db()->prepare("SELECT * FROM news WHERE slug = :slug AND status = 'published' AND published_at <= NOW() LIMIT 1");
$stmt->execute(['slug' => $slug]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    $pageTitle = 'Article Not Found — The Royal Palace of Benin';
    $pageDescription = 'The requested article could not be found.';
    $ogImage = 'assets/images/anniversary.jpg';
    $activeNav = 'news';
    require __DIR__ . '/includes/header.php';
    ?>
    <section class="section">
      <div class="shell" style="text-align:center">
        <span class="eyebrow">News &amp; Events</span>
        <h1 style="margin:1rem 0">Article Not Found</h1>
        <p class="lede" style="margin-inline:auto">This article may have been removed or unpublished.</p>
        <div class="btn-row" style="justify-content:center;margin-top:2rem"><a class="btn btn--royal" href="news.php">Back to News</a></div>
      </div>
    </section>
    <?php
    require __DIR__ . '/includes/footer.php';
    exit;
}

$featuredImageUrl = $post['featured_image']
    ? UPLOADS_NEWS_URL . '/' . $post['featured_image']
    : 'assets/images/anniversary.jpg';

$pageTitle       = $post['title'] . ' — The Royal Palace of Benin';
$pageDescription = $post['excerpt'] ?: excerpt_from_text($post['content'], 30);
$ogImage         = $featuredImageUrl;
$activeNav       = 'news';

// Related/recent news (excluding current)
$relStmt = db()->prepare("SELECT title, slug, featured_image, category, published_at FROM news
                           WHERE status = 'published' AND published_at <= NOW() AND id != :id
                           ORDER BY published_at DESC LIMIT 3");
$relStmt->execute(['id' => $post['id']]);
$related = $relStmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>
    <section class="phero">
      <img src="<?php echo e($featuredImageUrl); ?>" alt="" loading="eager">
      <div class="shell phero__inner">
        <p class="crumbs"><a href="index.php">Home</a> &nbsp;/&nbsp; <a href="news.php">News</a> &nbsp;/&nbsp; <?php echo e($post['title']); ?></p>
        <span class="eyebrow" style="margin-top:1.2rem"><?php echo e($post['category'] ?: 'Palace News'); ?></span>
        <h1><?php echo e($post['title']); ?></h1>
        <p class="lede">
          <?php echo e(format_date($post['published_at'])); ?><?php echo $post['author'] ? ' &middot; By ' . e($post['author']) : ''; ?>
        </p>
      </div>
    </section>

    <section class="section">
      <div class="shell" style="max-width:780px">
        <div class="reveal" style="font-size:1.08rem;line-height:1.85;color:var(--ink-70,#3a3a3a)">
          <?php echo $post['content']; /* stored as sanitized HTML from the admin editor */ ?>
        </div>
        <div class="btn-row" style="margin-top:2.6rem"><a class="btn btn--outline" href="news.php">&larr; Back to All News</a></div>
      </div>
    </section>

<?php if (!empty($related)): ?>
    <section class="section section--cream">
      <div class="shell">
        <div class="head reveal">
          <span class="eyebrow">Continue Reading</span>
          <h2>Related News</h2>
        </div>
        <div class="grid g3" style="margin-top:2.8rem">
<?php foreach ($related as $i => $r): ?>
          <a class="mcard reveal"<?php echo $i > 0 ? ' data-d="' . $i . '"' : ''; ?> href="article.php?slug=<?php echo urlencode($r['slug']); ?>">
            <img src="<?php echo e($r['featured_image'] ? UPLOADS_NEWS_URL . '/' . $r['featured_image'] : 'assets/images/anniversary.jpg'); ?>" alt="<?php echo e($r['title']); ?>" loading="lazy">
            <div class="mcard__body">
              <span class="mcard__tag"><?php echo e($r['category'] ?: 'Palace News'); ?></span>
              <h3><?php echo e($r['title']); ?></h3>
            </div>
          </a>
<?php endforeach; ?>
        </div>
      </div>
    </section>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
