<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'The Council of Chiefs — The Royal Palace of Benin';
$pageDescription = 'Meet the revered chiefs of the Royal Palace of Benin who counsel the throne and safeguard the traditions of the Benin Kingdom.';
$ogImage = 'assets/images/chiefs-procession.jpg';
$activeNav = 'council';

require __DIR__ . '/includes/header.php';
?>

    <section class="phero">
      <img src="assets/images/chiefs-procession.jpg" alt="" loading="eager">
      <div class="shell phero__inner">
        <p class="crumbs"><a href="index.php">Home</a> &nbsp;/&nbsp; Council</p>
        <span class="eyebrow" style="margin-top:1.2rem">Council of Chiefs</span>
        <h1>The Royal Council</h1>
        <p class="lede">The revered chiefs and palace nobility who counsel the throne, safeguard tradition, and steward the heritage of the Benin Kingdom.</p>
      </div>
    </section>

    <section class="section">
      <div class="shell">
        <div class="head reveal">
          <span class="eyebrow">The Council of Chiefs</span>
          <h2>Pillars of the Royal Court</h2>
          <p class="lede">The palace council comprises revered chiefs who, through generations of service, uphold the customs, counsel the throne, and preserve the sacred order of the Benin Kingdom.</p>
        </div>
        <div class="grid g3" style="margin-top:3.2rem">
          <article class="person reveal" data-d="1">
            <img src="assets/images/chief-1.jpg" alt="Chief Iyase N’Ore" loading="lazy">
            <div class="person__body">
              <span class="person__role">Prime Minister of the Realm</span>
              <h3>Chief Iyase N&rsquo;Ore</h3>
              <p class="person__title">Title · Iyase</p>
              <p>As the highest-ranking palace chief, the Iyase serves as the principal counsellor to His Royal Majesty, presiding over the council of state and safeguarding the wisdom of the kingdom. His authority spans civil affairs, royal arbitration, and the preservation of palace law.</p>
            </div>
          </article>
          <article class="person reveal" data-d="2">
            <img src="assets/images/chief-2.jpg" alt="Chief Esogban N’Edo" loading="lazy">
            <div class="person__body">
              <span class="person__role">Chief Custodian of Tradition</span>
              <h3>Chief Esogban N&rsquo;Edo</h3>
              <p class="person__title">Title · Esogban</p>
              <p>Keeper of the sacred customs and ancestral rites of the Benin Kingdom, the Esogban presides over the elder council and ensures every ceremony is performed in accordance with timeless tradition handed down from the ancients.</p>
            </div>
          </article>
          <article class="person reveal" data-d="3">
            <img src="assets/images/chief-3.jpg" alt="Chief Eson N’Ibiwe" loading="lazy">
            <div class="person__body">
              <span class="person__role">Voice of the Royal Court</span>
              <h3>Chief Eson N&rsquo;Ibiwe</h3>
              <p class="person__title">Title · Eson</p>
              <p>Spokesperson of the throne, the Eson conveys the words and decrees of His Royal Majesty to the people. He is the bridge between the palace and the kingdom, carrying royal pronouncements with dignity and clarity.</p>
            </div>
          </article>
          <article class="person reveal" data-d="1">
            <img src="assets/images/chief-4.jpg" alt="Chief Osuma N’Iwebo" loading="lazy">
            <div class="person__body">
              <span class="person__role">Guardian of the Palace Rites</span>
              <h3>Chief Osuma N&rsquo;Iwebo</h3>
              <p class="person__title">Title · Osuma</p>
              <p>Entrusted with the spiritual ceremonies of the Oba&rsquo;s chambers, the Osuma oversees the sacred rites that consecrate the throne and maintain harmony between the living, the ancestors, and the royal lineage.</p>
            </div>
          </article>
          <article class="person reveal" data-d="2">
            <img src="assets/images/chief-5.jpg" alt="Chief Ologbosere N’Edo" loading="lazy">
            <div class="person__body">
              <span class="person__role">Royal Commander &amp; Defender</span>
              <h3>Chief Ologbosere N&rsquo;Edo</h3>
              <p class="person__title">Title · Ologbosere</p>
              <p>The traditional commander of the kingdom&rsquo;s defenders, the Ologbosere stands as protector of the palace and the people. His office embodies the courage, valour, and unwavering loyalty of the Benin warrior tradition.</p>
            </div>
          </article>
          <article class="person reveal" data-d="3">
            <img src="assets/images/chief-6.jpg" alt="Chief Obahiagbon N’Iwoki" loading="lazy">
            <div class="person__body">
              <span class="person__role">Senior Palace Counsellor</span>
              <h3>Chief Obahiagbon N&rsquo;Iwoki</h3>
              <p class="person__title">Title · Obahiagbon</p>
              <p>A revered elder of the council, the Obahiagbon offers measured counsel on matters of heritage, succession, and palace diplomacy. His wisdom anchors the deliberations of the throne and the unity of chiefs.</p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section class="section section--royal">
      <div class="shell head head--center reveal">
        <span class="eyebrow eyebrow--center">Counsel &amp; Continuity</span>
        <h2>Service to the Throne, Service to the People</h2>
        <p class="lede" style="margin-inline:auto">Through the council, the wisdom of the ancients is carried into every deliberation of the palace.</p>
        <div class="btn-row" style="justify-content:center;margin-top:2rem"><a class="btn btn--gold" href="anniversary.php">10th Coronation Anniversary</a></div>
      </div>
    </section>
  
<?php require __DIR__ . '/includes/footer.php'; ?>
