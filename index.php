<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'The Royal Palace of Benin — Living Heritage of the Great Benin Kingdom';
$pageDescription = 'The Royal Palace of Benin: sacred seat of the Benin Kingdom in Edo State, Nigeria. Explore our heritage, royal council, and the 10th Coronation Anniversary of Oba Ewuare II.';
$ogImage = 'assets/images/hero-palace.jpg';
$activeNav = 'home';
$useImageLogo = true;

$latestNews = db()->query("SELECT title, slug, excerpt, category, published_at FROM news
                            WHERE status = 'published' AND published_at <= NOW()
                            ORDER BY published_at DESC LIMIT 3")->fetchAll();

$previewGallery = db()->query("SELECT title, image FROM gallery
                                WHERE status = 'active'
                                ORDER BY created_at DESC LIMIT 3")->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="hero__media"><img src="assets/images/hero-palace.jpg" alt="The Royal Palace of Benin at dusk"></div>
  <div class="shell hero__inner">
    <span class="eyebrow">Edo State &middot; Nigeria</span>
    <h1>The Living Heritage<em>of the <span class="gold-text">Great Benin Kingdom</span></em></h1>
    <p class="lede">The Royal Palace of Benin stands as a sacred symbol of history, honour, culture, and enduring
      traditional authority — preserving the legacy of one of Africa&rsquo;s most revered kingdoms.</p>
    <div class="btn-row">
      <a class="btn btn--gold" href="anniversary.php">Explore the 10th Anniversary</a>
      <a class="btn btn--ghost" href="heritage.php">Discover Our Heritage</a>
    </div>
    <div class="ticker">
      <div><b>VIII+</b><span>Centuries of Royal Heritage</span></div>
      <div><b>I</b><span>Sacred Throne Tradition</span></div>
      <div><b>&infin;</b><span>Preserver of Benin Culture</span></div>
      <div><b>&#10022;</b><span>Symbol of Unity &amp; Identity</span></div>
    </div>
  </div>
  <div class="hero__scroll" aria-hidden="true"><i></i>Scroll</div>
</section>

<section class="section">
  <div class="shell split">
    <div class="split__media reveal">
      <img src="assets/images/palace-exterior.jpg" alt="The Royal Palace of Benin in Benin City, Edo State"
        loading="lazy">
      <div class="split__badge"><b>Edo</b><span>Kingdom · Nigeria</span></div>
    </div>
    <div class="split__body reveal" data-d="1">
      <span class="eyebrow">About the Palace</span>
      <h2 style="margin:1rem 0 1.2rem">The Sacred Heart of the Benin Kingdom</h2>
      <div class="rule"></div>
      <p class="lede">The Royal Palace of Benin is the spiritual, cultural, and traditional heart of the Benin
        Kingdom in Edo State, Nigeria. It is a sacred institution that preserves the continuity of monarchy,
        ancestral heritage, and the identity of the Benin people.</p>
      <p class="lede">For generations, the palace has stood as a revered seat of authority, custom, ceremony, and
        community leadership. It remains a place where history lives, where tradition is honoured, and where the
        values of dignity, wisdom, courage, and service continue to guide the people.</p>
      <div class="btn-row">
        <a class="btn btn--royal" href="heritage.php">Explore Heritage</a>
        <a class="btn btn--outline" href="about.php">About the Palace</a>
      </div>
    </div>
  </div>
</section>

<!-- ANNIVERSARY FEATURE -->
<section class="section anniv-band">
  <div class="anniv-band__bg"><img src="assets/images/anniversary.jpg" alt="" loading="lazy"></div>
  <div class="shell">
    <div class="anniv-grid">
      <div class="reveal">
        <span class="eyebrow">3 &ndash; 25 October 2026 &middot; Benin City</span>
        <h2 style="margin:1.1rem 0 1rem">10th Coronation <span class="gold-text">Anniversary</span></h2>
        <div class="rule"></div>
        <p class="lede">Celebrating a decade of restoration, culture, and calm under the reign of His Royal Majesty
          Omo N&rsquo;Oba N&rsquo;Edo Uku Akpolokpolo, Ewuare II, CFR, 40th Oba of Benin Kingdom.</p>
        <p class="royal-quote" style="margin-top:1.4rem">&ldquo;Oba ghato kpere, Ise!!!&rdquo;</p>
        <div class="countdown" data-countdown="2026-10-03T00:00:00">
          <div><b>00</b><span>Days</span></div>
          <div><b>00</b><span>Hours</span></div>
          <div><b>00</b><span>Minutes</span></div>
          <div><b>00</b><span>Seconds</span></div>
        </div>
        <div class="btn-row" style="margin-top:2.2rem">
          <a class="btn btn--gold" href="anniversary.php">Explore Anniversary</a>
          <a class="btn btn--ghost" href="anniversary.php#programme">View Full Programme</a>
        </div>
      </div>
      <div class="anniv-emblem reveal" data-d="2">
        <img src="assets/images/anniversary-logo.jpg"
          alt="Official logo of the 10th Coronation Anniversary of Oba Ewuare II" loading="lazy">
      </div>
    </div>
    <div class="stats reveal" style="margin-top:4rem">
      <div><b data-count="14">14</b><span>Major Events</span></div>
      <div><b data-count="23">23</b><span>Days of Celebration</span></div>
      <div><b data-count="119">119</b><span>Artefacts Returned, 2025</span></div>
      <div><b data-count="800" data-suffix="+">800+</b><span>Years of Tradition</span></div>
    </div>
  </div>
</section>

<section class="section section--cream">
  <div class="shell">
    <div class="head head--center reveal">
      <span class="eyebrow eyebrow--center">The Pillars of the Kingdom</span>
      <h2>Six Enduring Roles of the Palace</h2>
      <p class="lede" style="margin-inline:auto">Six enduring roles through which the Royal Palace of Benin upholds
        the heritage and dignity of the Edo people.</p>
    </div>
    <div class="grid g3" style="margin-top:3.2rem">
      <article class="card reveal" data-d="1">
        <span class="card__num">I</span>
        <h3>Sacred Royal Heritage</h3>
        <p>An unbroken lineage of revered Obas, custodians of ancestral wisdom and divine authority.</p>
      </article>
      <article class="card reveal" data-d="2">
        <span class="card__num">II</span>
        <h3>Culture and Tradition</h3>
        <p>Living customs, language, and rites passed across generations of the Benin people.</p>
      </article>
      <article class="card reveal" data-d="3">
        <span class="card__num">III</span>
        <h3>Community Leadership</h3>
        <p>A guiding voice for unity, justice, and the welfare of all sons and daughters of the Kingdom.</p>
      </article>
      <article class="card reveal" data-d="1">
        <span class="card__num">IV</span>
        <h3>Historical Preservation</h3>
        <p>Guardian of bronzes, ivories, archives, and the memory of one of Africa&rsquo;s greatest civilizations.
        </p>
      </article>
      <article class="card reveal" data-d="2">
        <span class="card__num">V</span>
        <h3>Royal Ceremonies</h3>
        <p>Sacred rites, festivals, and palace observances that mark the rhythm of the kingdom.</p>
      </article>
      <article class="card reveal" data-d="3">
        <span class="card__num">VI</span>
        <h3>Legacy for Generations</h3>
        <p>A living inheritance entrusted to the children of Benin — yesterday, today, and tomorrow.</p>
      </article>
    </div>
  </div>
</section>

<!-- HERITAGE -->
<section class="section">
  <div class="shell">
    <div class="head reveal">
      <span class="eyebrow">Tradition &middot; Culture &middot; Heritage</span>
      <h2>The Soul of a Great Kingdom</h2>
      <p class="lede">Centuries of bronzes, regalia, ceremonies, and customs that continue to shape the identity of
        the Benin people.</p>
    </div>
    <div class="grid g4" style="margin-top:3rem">
      <a class="mcard reveal" href="heritage.php"><img src="assets/images/cultural-dance.jpg"
          alt="Traditional Edo cultural dance" loading="lazy">
        <div class="mcard__body"><span class="mcard__tag">Living Customs</span>
          <h3>Benin Tradition</h3>
          <p>Customs, language, and sacred values that have guided generations.</p>
        </div>
      </a>
      <a class="mcard reveal" data-d="1" href="heritage.php"><img src="assets/images/benin-bronze.jpg"
          alt="A Benin bronze plaque" loading="lazy">
        <div class="mcard__body"><span class="mcard__tag">Bronze &amp; Ivory</span>
          <h3>Cultural Heritage</h3>
          <p>World-renowned masterpieces telling the story of a great civilization.</p>
        </div>
      </a>
      <a class="mcard reveal" data-d="2" href="heritage.php"><img src="assets/images/chiefs-procession.jpg"
          alt="Chiefs in ceremonial procession" loading="lazy">
        <div class="mcard__body"><span class="mcard__tag">Sacred Rites</span>
          <h3>Royal Ceremonies</h3>
          <p>Festivals, processions, and rites that mark the rhythm of the kingdom.</p>
        </div>
      </a>
      <a class="mcard reveal" data-d="3" href="heritage.php"><img src="assets/images/coral-beads.jpg"
          alt="Coral bead royal regalia" loading="lazy">
        <div class="mcard__body"><span class="mcard__tag">Royal Regalia</span>
          <h3>Symbols of Authority</h3>
          <p>Coral beads, ceremonial attire, and emblems that adorn royalty.</p>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- THE THRONE -->
<section class="section section--ink">
  <div class="shell split split--flip">
    <div class="split__media reveal">
      <img src="assets/images/oba-portrait.jpg" alt="Portrait honouring the throne of the Oba of Benin" loading="lazy">
    </div>
    <div class="split__body reveal" data-d="1">
      <span class="eyebrow">The Throne &middot; Royal Leadership</span>
      <h2 style="margin:1rem 0 1.2rem">Sacred Custodian of the Kingdom&rsquo;s Soul</h2>
      <div class="rule"></div>
      <p class="lede">The Oba of Benin is the sacred custodian of the kingdom&rsquo;s heritage, customs, and
        ancestral authority. The throne represents continuity, identity, honour, and spiritual responsibility — a
        sacred bond between the people, the land, and the ancestors.</p>
      <p class="lede">Surrounded by a council of revered chiefs and palace nobility, His Royal Majesty presides over
        the rites, ceremonies, and counsel that uphold the dignity of the Benin people.</p>
      <ul class="infolist" style="margin-top:1.6rem">
        <li><span><strong>Iyase</strong> — Prime Minister of the Realm</span></li>
        <li><span><strong>Esogban</strong> — Chief Custodian of Tradition</span></li>
        <li><span><strong>Eson</strong> — Voice of the Royal Court</span></li>
        <li><span><strong>Osuma</strong> — Guardian of the Palace Rites</span></li>
      </ul>
      <div class="btn-row" style="margin-top:2rem"><a class="btn btn--gold" href="council.php">Meet the Royal
          Council</a></div>
    </div>
  </div>
</section>

<!-- NEWS -->
<section class="section section--cream">
  <div class="shell">
    <div class="head reveal">
      <span class="eyebrow">From the Palace Court</span>
      <h2>Latest News &amp; Events</h2>
      <p class="lede">Anniversary news, cultural celebrations, royal events, and heritage preservation activities of
        the Benin Kingdom.</p>
    </div>
    <div class="grid g3" style="margin-top:3rem">
      <?php if (empty($latestNews)): ?>
        <article class="card reveal"><span class="card__num">Anniversary &middot; Coming Season</span>
          <h3>Royal Anniversary Procession Announced</h3>
          <p>His Royal Majesty&rsquo;s grand commemorative procession to honour the kingdom&rsquo;s enduring legacy.
          </p>
          <p style="margin-top:1.2rem"><a class="textlink" href="news.php">Read More</a></p>
        </article>
        <article class="card reveal" data-d="1"><span class="card__num">Cultural Festival &middot; Festival
            Season</span>
          <h3>Annual Cultural Festival Returns</h3>
          <p>Traditional music, dance, and storytelling unite the people of Benin in joyous celebration.</p>
          <p style="margin-top:1.2rem"><a class="textlink" href="news.php">Read More</a></p>
        </article>
        <article class="card reveal" data-d="2"><span class="card__num">Heritage Preservation &middot; Ongoing</span>
          <h3>Restoration of Royal Bronze Collection</h3>
          <p>A renewed initiative to preserve and document the priceless artistic legacy of the kingdom.</p>
          <p style="margin-top:1.2rem"><a class="textlink" href="news.php">Read More</a></p>
        </article>
      <?php else: ?>
        <?php foreach ($latestNews as $i => $post): ?>
          <article class="card reveal" <?php echo $i > 0 ? ' data-d="' . $i . '"' : ''; ?>><span
              class="card__num"><?php echo e($post['category'] ?: 'Palace News'); ?> &middot;
              <?php echo e(format_date($post['published_at'])); ?></span>
            <h3><?php echo e($post['title']); ?></h3>
            <p><?php echo e($post['excerpt'] ?: excerpt_from_text($post['title'], 20)); ?></p>
            <p style="margin-top:1.2rem"><a class="textlink"
                href="article.php?slug=<?php echo urlencode($post['slug']); ?>">Read More</a></p>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- GALLERY PREVIEW -->
<section class="section">
  <div class="shell">
    <div class="head head--center reveal">
      <span class="eyebrow eyebrow--center">The Royal Gallery</span>
      <h2>Moments of Royal Splendour</h2>
    </div>
    <div class="grid g3" style="margin-top:2.8rem">
      <?php if (empty($previewGallery)): ?>
        <a class="mcard reveal" href="gallery.php"><img src="assets/images/throne-room.jpg" alt="The royal throne room"
            loading="lazy">
          <div class="mcard__body">
            <h3>The Throne Room</h3>
          </div>
        </a>
        <a class="mcard reveal" data-d="1" href="gallery.php"><img src="assets/images/ivory-mask.jpg"
            alt="Benin ivory mask" loading="lazy">
          <div class="mcard__body">
            <h3>Ivory &amp; Bronze</h3>
          </div>
        </a>
        <a class="mcard reveal" data-d="2" href="gallery.php"><img src="assets/images/anniversary.jpg"
            alt="Anniversary celebration" loading="lazy">
          <div class="mcard__body">
            <h3>Anniversary Splendour</h3>
          </div>
        </a>
      <?php else: ?>
        <?php foreach ($previewGallery as $i => $img): ?>
          <a class="mcard reveal" <?php echo $i > 0 ? ' data-d="' . $i . '"' : ''; ?> href="gallery.php"><img
              src="<?php echo e(UPLOADS_GALLERY_URL . '/' . $img['image']); ?>"
              alt="<?php echo e($img['title'] ?: 'Royal Palace of Benin'); ?>" loading="lazy">
            <div class="mcard__body">
              <h3><?php echo e($img['title']); ?></h3>
            </div>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="head head--center" style="margin-top:2.6rem"><a class="btn btn--outline" href="gallery.php">View
        Full Gallery</a></div>
  </div>
</section>

<!-- CTA -->
<section class="section section--ink cta-band">
  <img src="assets/images/chiefs-procession.jpg" alt="" loading="lazy">
  <div class="shell">
    <span class="eyebrow eyebrow--center reveal">Join the Celebration</span>
    <h2 class="reveal" style="margin:1.2rem 0 1rem">A Kingdom Invites the World</h2>
    <p class="lede reveal" style="margin-inline:auto">Fourteen major events across Benin City between 3 and 25
      October 2026 mark a decade of the reign of Oba Ewuare II. Explore the programme and be part of the
      celebration.</p>
    <div class="btn-row reveal" style="justify-content:center;margin-top:2.2rem">
      <a class="btn btn--gold" href="anniversary.php">Explore Anniversary</a>
      <a class="btn btn--ghost" href="contact.php">Contact the Palace</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>