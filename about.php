<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'About the Palace — The Royal Palace of Benin';
$pageDescription = 'The Royal Palace of Benin is the spiritual, cultural, and traditional heart of the Benin Kingdom in Edo State, Nigeria.';
$ogImage = 'assets/images/palace-exterior.jpg';
$activeNav = 'about';

require __DIR__ . '/includes/header.php';
?>

    <section class="phero">
      <img src="assets/images/palace-exterior.jpg" alt="" loading="eager">
      <div class="shell phero__inner">
        <p class="crumbs"><a href="index.php">Home</a> &nbsp;/&nbsp; About</p>
        <span class="eyebrow" style="margin-top:1.2rem">About the Palace</span>
        <h1>The Sacred Heart of Benin</h1>
        <p class="lede">A revered seat of authority, custom, ceremony, and community leadership — preserving the legacy of one of Africa&rsquo;s most respected kingdoms.</p>
      </div>
    </section>

    <section class="section">
      <div class="shell split">
        <div class="split__media reveal">
          <img src="assets/images/palace-exterior.jpg" alt="The Royal Palace of Benin in Benin City, Edo State" loading="lazy">
          <div class="split__badge"><b>Edo</b><span>Kingdom · Nigeria</span></div>
        </div>
        <div class="split__body reveal" data-d="1">
          <span class="eyebrow">About the Palace</span>
          <h2 style="margin:1rem 0 1.2rem">The Sacred Heart of the Benin Kingdom</h2>
          <div class="rule"></div>
          <p class="lede">The Royal Palace of Benin is the spiritual, cultural, and traditional heart of the Benin Kingdom in Edo State, Nigeria. It is a sacred institution that preserves the continuity of monarchy, ancestral heritage, and the identity of the Benin people.</p>
          <p class="lede">For generations, the palace has stood as a revered seat of authority, custom, ceremony, and community leadership. It remains a place where history lives, where tradition is honoured, and where the values of dignity, wisdom, courage, and service continue to guide the people.</p>
          <div class="btn-row">
            <a class="btn btn--royal" href="heritage.php">Explore Heritage</a>
            <a class="btn btn--outline" href="about.php">About the Palace</a>
          </div>
        </div>
      </div>
    </section>

    <section class="section section--cream">
      <div class="shell">
        <div class="head head--center reveal">
          <span class="eyebrow eyebrow--center">The Pillars of the Kingdom</span>
          <h2>Six Enduring Roles of the Palace</h2>
          <p class="lede" style="margin-inline:auto">Six enduring roles through which the Royal Palace of Benin upholds the heritage and dignity of the Edo people.</p>
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
          <p>Guardian of bronzes, ivories, archives, and the memory of one of Africa&rsquo;s greatest civilizations.</p>
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

    <section class="section section--ink">
      <div class="shell split split--flip">
        <div class="split__media reveal"><img src="assets/images/oba-portrait.jpg" alt="Portrait honouring the throne of the Oba of Benin" loading="lazy"></div>
        <div class="split__body reveal" data-d="1">
          <span class="eyebrow">His Royal Majesty &middot; The Oba of Benin</span>
          <h2 style="margin:1rem 0 1.2rem">Sacred Custodian of the Kingdom&rsquo;s Soul</h2>
          <div class="rule"></div>
          <p class="lede">The Oba of Benin is the sacred custodian of the kingdom&rsquo;s heritage, customs, and ancestral authority. The throne represents continuity, identity, honour, and spiritual responsibility — a sacred bond between the people, the land, and the ancestors.</p>
          <p class="lede">Surrounded by a council of revered chiefs and palace nobility, His Royal Majesty presides over the rites, ceremonies, and counsel that uphold the dignity of the Benin people.</p>
          <ul class="infolist" style="margin-top:1.6rem">
            <li><span><strong>Iyase</strong> — Prime Minister of the Realm</span></li>
            <li><span><strong>Esogban</strong> — Chief Custodian of Tradition</span></li>
            <li><span><strong>Eson</strong> — Voice of the Royal Court</span></li>
            <li><span><strong>Osuma</strong> — Guardian of the Palace Rites</span></li>
          </ul>
          <div class="btn-row" style="margin-top:2rem"><a class="btn btn--gold" href="council.php">The Council of Chiefs</a></div>
        </div>
      </div>
    </section>

    <section class="section section--cream">
      <div class="shell head head--center reveal">
        <span class="eyebrow eyebrow--center">Established in Antiquity</span>
        <h2>A Palace Presence Across the Centuries</h2>
        <p class="lede" style="margin-inline:auto">From ancient foundations to present-day cultural leadership, the palace endures as the centre of Benin identity.</p>
        <div class="btn-row" style="justify-content:center;margin-top:2rem"><a class="btn btn--royal" href="heritage.php">The Royal Chronicle</a></div>
      </div>
    </section>
  
<?php require __DIR__ . '/includes/footer.php'; ?>
