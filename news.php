<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle       = 'News & Events — The Royal Palace of Benin';
$pageDescription = 'Royal announcements, cultural festivals, and heritage preservation news from the Royal Palace of Benin.';
$ogImage         = 'assets/images/anniversary.jpg';
$activeNav       = 'news';

// Pagination
$perPage = 9;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

$total = (int) db()->query("SELECT COUNT(*) FROM news WHERE status = 'published' AND published_at <= NOW()")->fetchColumn();
$totalPages = max(1, (int) ceil($total / $perPage));

$stmt = db()->prepare("SELECT id, title, slug, excerpt, featured_image, category, published_at
                        FROM news
                        WHERE status = 'published' AND published_at <= NOW()
                        ORDER BY published_at DESC
                        LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>
    <section class="phero">
      <img src="assets/images/anniversary.jpg" alt="" loading="eager">
      <div class="shell phero__inner">
        <p class="crumbs"><a href="index.php">Home</a> &nbsp;/&nbsp; News</p>
        <span class="eyebrow" style="margin-top:1.2rem">News &amp; Events</span>
        <h1>From the Palace Court</h1>
        <p class="lede">Anniversary news, cultural celebrations, royal events, and heritage preservation activities of the Benin Kingdom.</p>
      </div>
    </section>

    <section class="section">
      <div class="shell">
<?php if (empty($posts)): ?>
        <div class="head reveal">
          <p class="lede">No news has been published yet. Please check back soon.</p>
        </div>
<?php else: ?>
        <div class="grid g3">
<?php foreach ($posts as $i => $post): ?>
          <article class="card reveal"<?php echo $i > 0 ? ' data-d="' . ($i % 3) . '"' : ''; ?>>
            <span class="card__num"><?php echo e($post['category'] ?: 'Palace News'); ?> &middot; <?php echo e(format_date($post['published_at'])); ?></span>
            <h3><?php echo e($post['title']); ?></h3>
            <p><?php echo e($post['excerpt'] ?: excerpt_from_text($post['title'], 20)); ?></p>
            <p style="margin-top:1.2rem"><a class="textlink" href="article.php?slug=<?php echo urlencode($post['slug']); ?>">Read More</a></p>
          </article>
<?php endforeach; ?>
        </div>
<?php if ($totalPages > 1): ?>
        <div class="btn-row" style="margin-top:2.6rem;justify-content:center">
<?php if ($page > 1): ?>
          <a class="btn btn--outline" href="news.php?page=<?php echo $page - 1; ?>">&larr; Newer</a>
<?php endif; ?>
          <span style="align-self:center;color:var(--ink-60,#666)">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
<?php if ($page < $totalPages): ?>
          <a class="btn btn--outline" href="news.php?page=<?php echo $page + 1; ?>">Older &rarr;</a>
<?php endif; ?>
        </div>
<?php endif; ?>
<?php endif; ?>
      </div>
    </section>

    <section class="section section--cream">
      <div class="shell">
        <div class="head reveal">
          <span class="eyebrow">Anniversary Bulletin</span>
          <h2>Latest Announcements</h2>
          <p class="lede">Announcements published on the official 10th Coronation Anniversary platform.</p>
        </div>
        <div class="grid g2" style="margin-top:2.6rem">
          <div class="notice reveal"><time>17 July 2026</time><h3>Test Announcement</h3><p>This is a test announcement for the coronation anniversary.</p></div>
          <div class="notice reveal" data-d="1"><time>17 July 2026</time><h3>Test Announcement</h3><p>This is a test announcement for the coronation anniversary.</p></div>
          <div class="notice reveal" data-d="2"><time>10 May 2026</time><h3>Test Announcement</h3><p>This is a test announcement for the coronation anniversary.</p></div>
          <div class="notice reveal" data-d="3"><time>10 May 2026</time><h3>Test Announcement</h3><p>This is a test announcement for the coronation anniversary.</p></div>
        </div>
        <div class="btn-row" style="margin-top:2.4rem"><a class="btn btn--royal" href="anniversary.php">Full Anniversary Programme</a></div>
      </div>
    </section>
<?php require __DIR__ . '/includes/footer.php'; ?>
