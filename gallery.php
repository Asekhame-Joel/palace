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

$videos = db()->query("SELECT title, video_type, description, youtube_id, youtube_url, video_file, thumbnail FROM gallery_videos
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

<?php if (!empty($videos)): ?>
    <nav class="gallery-media-nav" aria-label="Gallery sections">
      <div class="shell">
        <a class="is-active" href="#gallery-videos"><span>Watch</span> Videos <b><?php echo count($videos); ?></b></a>
        <a href="#gallery-photos"><span>Explore</span> Photos <b><?php echo count($images); ?></b></a>
      </div>
    </nav>

    <section class="section gallery-videos" id="gallery-videos">
      <div class="shell">
        <div class="gallery-videos__head reveal">
          <div>
            <span class="eyebrow">Palace on Film</span>
            <h2>Watch Our Stories</h2>
          </div>
          <!-- <p class="lede">Royal ceremonies, heritage, reports, and memorable moments from the Benin Kingdom.</p> -->
        </div>
        <div class="video-grid">
<?php foreach ($videos as $i => $video): ?>
<?php if ($video['video_type'] === 'upload'): ?>
          <article class="video-card video-card--upload reveal" data-uploaded-video<?php echo $i > 0 ? ' data-d="' . ($i % 3) . '"' : ''; ?>>
            <span class="video-card__media">
              <video controls playsinline preload="metadata" poster="<?php echo e(UPLOADS_VIDEO_URL . '/' . $video['thumbnail']); ?>" aria-label="<?php echo e($video['title']); ?>" data-video-element>
                <source src="<?php echo e(UPLOADS_VIDEO_URL . '/' . $video['video_file']); ?>" type="<?php echo e(video_mime_type($video['video_file'])); ?>">
                Your browser does not support embedded video playback.
              </video>
              <button class="video-card__play video-card__play--local" type="button" aria-label="Play <?php echo e($video['title']); ?>" data-video-play><span aria-hidden="true"></span></button>
              <span class="video-card__source">Palace Video</span>
            </span>
            <span class="video-card__body">
              <span class="video-card__label">Royal Palace Video</span>
              <strong><?php echo e($video['title']); ?></strong>
<?php if ($video['description']): ?>
              <span class="video-card__description"><?php echo nl2br(e($video['description'])); ?></span>
<?php endif; ?>
            </span>
          </article>
<?php else: ?>
          <a class="video-card reveal"<?php echo $i > 0 ? ' data-d="' . ($i % 3) . '"' : ''; ?> href="<?php echo e($video['youtube_url']); ?>" target="_blank" rel="noopener noreferrer" aria-label="Watch <?php echo e($video['title']); ?> on YouTube">
            <span class="video-card__media">
              <img src="https://i.ytimg.com/vi/<?php echo e($video['youtube_id']); ?>/hqdefault.jpg" alt="<?php echo e($video['title']); ?>" loading="lazy">
              <span class="video-card__play" aria-hidden="true"><span></span></span>
              <span class="video-card__source">YouTube</span>
            </span>
            <span class="video-card__body">
              <span class="video-card__label">Royal Palace Video</span>
              <strong><?php echo e($video['title']); ?></strong>
              <span class="video-card__watch">Watch on YouTube <span aria-hidden="true">&#8599;</span></span>
            </span>
          </a>
<?php endif; ?>
<?php endforeach; ?>
        </div>
      </div>
    </section>
<?php endif; ?>

    <section class="section" id="gallery-photos">
      <div class="shell">
        <div class="gallery-photos__head reveal">
          <span class="eyebrow">In Pictures</span>
          <h2>The Palace &amp; Its Treasures</h2>
          <p class="lede">Explore royal ceremonies, distinguished visitors, palace life, and the enduring visual heritage of the Benin Kingdom.</p>
        </div>
<?php if (!empty($categories)): ?>
        <div class="btn-row gallery-filters reveal" data-gallery-filters>
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
