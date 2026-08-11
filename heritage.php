<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Tradition & Heritage — The Royal Palace of Benin';
$pageDescription = 'Bronzes, regalia, ceremonies, and customs of the Benin Kingdom, with a royal chronicle from ancient foundations to today.';
$ogImage = 'assets/images/benin-bronze.jpg';
$activeNav = 'heritage';

require __DIR__ . '/includes/header.php';
?>

    <section class="phero">
      <img src="assets/images/benin-bronze.jpg" alt="" loading="eager">
      <div class="shell phero__inner">
        <p class="crumbs"><a href="index.php">Home</a> &nbsp;/&nbsp; Heritage</p>
        <span class="eyebrow" style="margin-top:1.2rem">Tradition &middot; Culture &middot; Heritage</span>
        <h1>The Soul of a Great Kingdom</h1>
        <p class="lede">Centuries of bronzes, regalia, ceremonies, and customs that continue to shape the identity of the Benin people.</p>
      </div>
    </section>

    <section class="section">
      <div class="shell">
        <div class="head reveal">
          <span class="eyebrow">Four Living Traditions</span>
          <h2>A Celebration of Benin Culture</h2>
          <p class="lede">A celebration of Benin&rsquo;s bronzes, royal regalia, sacred symbols, festivals, and the court traditions that continue to inspire the world.</p>
        </div>
        <div class="grid g4" style="margin-top:3rem">
          <article class="mcard reveal"><img src="assets/images/cultural-dance.jpg" alt="Traditional Edo dance" loading="lazy"><div class="mcard__body"><span class="mcard__tag">Living Customs</span><h3>Benin Tradition</h3><p>Customs, language, and sacred values that have guided generations of the Benin people.</p></div></article>
          <article class="mcard reveal" data-d="1"><img src="assets/images/benin-bronze.jpg" alt="Benin bronze" loading="lazy"><div class="mcard__body"><span class="mcard__tag">Bronze &amp; Ivory</span><h3>Cultural Heritage</h3><p>World-renowned bronzes and ivories — masterpieces telling the story of a great civilization.</p></div></article>
          <article class="mcard reveal" data-d="2"><img src="assets/images/chiefs-procession.jpg" alt="Ceremonial procession" loading="lazy"><div class="mcard__body"><span class="mcard__tag">Sacred Rites</span><h3>Royal Ceremonies</h3><p>Sacred festivals, processions, and rites that mark the spiritual rhythm of the kingdom.</p></div></article>
          <article class="mcard reveal" data-d="3"><img src="assets/images/coral-beads.jpg" alt="Coral beads" loading="lazy"><div class="mcard__body"><span class="mcard__tag">Royal Regalia</span><h3>Regalia &amp; Symbols</h3><p>Coral beads, ceremonial attire, and emblems that adorn royalty and signify authority.</p></div></article>
        </div>
      </div>
    </section>

    <section class="section section--cream">
      <div class="shell split">
        <div class="split__body reveal">
          <span class="eyebrow">A Kingdom Through Time</span>
          <h2 style="margin:1rem 0 1.2rem">The Royal Chronicle</h2>
          <div class="rule"></div>
          <p class="lede" style="margin-bottom:2.6rem">A timeline of the Benin Kingdom and its enduring palace — from ancient foundations to present-day cultural leadership.</p>
          <div class="timeline">
          <div class="tl reveal"><span class="tl__year">Ancient Era</span><h3>Foundation of the Benin Kingdom</h3><p>The kingdom emerges as one of West Africa&rsquo;s most influential civilizations, rooted in sacred kingship and order.</p></div>
          <div class="tl reveal"><span class="tl__year">Classical Period</span><h3>Generations of Revered Obas</h3><p>An unbroken lineage of monarchs shapes the spiritual, political, and artistic identity of the people.</p></div>
          <div class="tl reveal"><span class="tl__year">Golden Age</span><h3>The Age of Bronze and Ivory</h3><p>Master guilds craft world-renowned bronzes and ivories that adorn the palace and tell the kingdom&rsquo;s story.</p></div>
          <div class="tl reveal"><span class="tl__year">Modern Era</span><h3>Preservation of Heritage</h3><p>Through changing times, the palace safeguards customs, regalia, and the dignity of Benin tradition.</p></div>
          <div class="tl reveal"><span class="tl__year">Today</span><h3>Living Cultural Leadership</h3><p>The palace continues as a sacred seat of guidance, ceremony, and unity for the Benin people and the world.</p></div>
          </div>
        </div>
        <div class="split__media reveal" data-d="1"><img src="assets/images/throne-room.jpg" alt="The royal throne room of the Palace of Benin" loading="lazy"></div>
      </div>
    </section>

    <section class="section">
      <div class="shell" style="max-width:62rem">
        <div class="head reveal">
          <span class="eyebrow">Visitors &amp; Cultural Education</span>
          <h2>A Welcome to Heritage</h2>
          <p class="lede">Step into the living history of Benin. Learn the stories, symbols, and traditions that have shaped a kingdom through the ages.</p>
        </div>
        <div class="acc reveal" style="margin-top:2.5rem">
          <div class="acc__item">
            <button class="acc__btn" type="button" aria-expanded="false"><span style="color:var(--gold-500);font-size:.85rem;letter-spacing:.2em;margin-right:1rem">I</span>History of the Benin Kingdom</button>
            <div class="acc__panel"><p>The origins, lineage, and rise of one of Africa&rsquo;s most influential civilizations.</p></div>
          </div>
          <div class="acc__item">
            <button class="acc__btn" type="button" aria-expanded="false"><span style="color:var(--gold-500);font-size:.85rem;letter-spacing:.2em;margin-right:1rem">II</span>Meaning of Royal Traditions</button>
            <div class="acc__panel"><p>The sacred symbolism behind ceremonies, regalia, and the rites of the throne.</p></div>
          </div>
          <div class="acc__item">
            <button class="acc__btn" type="button" aria-expanded="false"><span style="color:var(--gold-500);font-size:.85rem;letter-spacing:.2em;margin-right:1rem">III</span>Palace Customs &amp; Etiquette</button>
            <div class="acc__panel"><p>Codes of respect, address, and conduct observed within the royal court.</p></div>
          </div>
          <div class="acc__item">
            <button class="acc__btn" type="button" aria-expanded="false"><span style="color:var(--gold-500);font-size:.85rem;letter-spacing:.2em;margin-right:1rem">IV</span>Benin Arts &amp; Symbolism</button>
            <div class="acc__panel"><p>Bronzes, ivories, and motifs that carry the visual memory of the kingdom.</p></div>
          </div>
          <div class="acc__item">
            <button class="acc__btn" type="button" aria-expanded="false"><span style="color:var(--gold-500);font-size:.85rem;letter-spacing:.2em;margin-right:1rem">V</span>Traditional Institutions &amp; Titles</button>
            <div class="acc__panel"><p>The chiefs, councils, and offices that uphold the dignity of the palace.</p></div>
          </div>
          <div class="acc__item">
            <button class="acc__btn" type="button" aria-expanded="false"><span style="color:var(--gold-500);font-size:.85rem;letter-spacing:.2em;margin-right:1rem">VI</span>Festivals &amp; Sacred Calendar</button>
            <div class="acc__panel"><p>Annual rites and celebrations that mark the spiritual rhythm of the people.</p></div>
          </div>
        </div>
      </div>
    </section>
  
<?php require __DIR__ . '/includes/footer.php'; ?>
