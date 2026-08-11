<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle       = 'The Royal Gallery — The Royal Palace of Benin';
$pageDescription = 'A gallery of the Royal Palace of Benin: regalia, bronzes, ceremonies, processions, and anniversary celebration imagery.';
$ogImage         = 'assets/images/throne-room.jpg';
$activeNav       = 'gallery';

$images = db()->query("SELECT id, title, description, image, category FROM gallery
                        WHERE status = 'active' ORDER BY created_at DESC")->fetchAll();

$categories = [];
foreach ($images as $img) {
    if (!empty($img['category']) && !in_array($img['category'], $categories, true)) {
        $categories[] = $img['category'];
    }
}

require __DIR__ . '/includes/header.php';
?>
    <section class="phero">
      <img src="assets/images/throne-room.jpg" alt="" loading="eager">
      <div class="shell phero__inner">
        <p class="crumbs"><a href="index.php">Home</a> &nbsp;/&nbsp; Gallery</p>
        <span class="eyebrow" style="margin-top:1.2rem">The Royal Gallery</span>
        <h1>Moments of Royal Splendour</h1>
        <p class="lede">Photography of the palace, its regalia, its ceremonies, and the artistry of the Benin Kingdom.
        </p>
      </div>
    </section>

    <section class="section">
      <div class="shell">
<?php if (!empty($categories)): ?>
        <div class="btn-row gallery-filters reveal" style="margin-bottom:2.4rem" data-gallery-filters>
          <button type="button" class="btn btn--outline is-active" data-filter="*">All</button>
<?php foreach ($categories as $cat): ?>
          <button type="button" class="btn btn--outline" data-filter="<?php echo e($cat); ?>"><?php echo e($cat); ?></button>
<?php endforeach; ?>
        </div>
<?php endif; ?>
<?php if (empty($images)): ?>
        <p class="lede">No gallery images have been added yet. Please check back soon.</p>
<?php else: ?>
        <div class="masonry" data-lightbox-gallery>
<?php foreach ($images as $img): ?>
          <figure class="reveal"<?php echo $img['category'] ? ' data-category="' . e($img['category']) . '"' : ''; ?>>
            <img src="<?php echo e(UPLOADS_GALLERY_URL . '/' . $img['image']); ?>" alt="<?php echo e($img['title'] ?: 'Royal Palace of Benin'); ?>" loading="lazy" data-lightbox-src="<?php echo e(UPLOADS_GALLERY_URL . '/' . $img['image']); ?>" data-lightbox-caption="<?php echo e($img['title']); ?>">
            <figcaption><?php echo e($img['title']); ?></figcaption>
          </figure>
<?php endforeach; ?>
        </div>
<?php endif; ?>
      </div>
    </section>

    <div class="lightbox" id="lightbox" data-lightbox-root hidden>
      <button type="button" class="lightbox__close" data-lightbox-close aria-label="Close">&times;</button>
      <figure>
        <img src="" alt="" data-lightbox-image>
        <figcaption data-lightbox-figcaption></figcaption>
      </figure>
    </div>
<?php require __DIR__ . '/includes/footer.php'; ?>
