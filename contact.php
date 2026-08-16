<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Contact — The Royal Palace of Benin';
$pageDescription = 'Contact the Royal Palace of Benin for heritage enquiries, visits, media requests, and 10th Coronation Anniversary information.';
$ogImage = 'assets/images/palace-exterior.jpg';
$activeNav = 'contact';

require __DIR__ . '/includes/header.php';
?>

<section class="phero">
  <img src="assets/images/palace-exterior.jpg" alt="" loading="eager">

  <div class="shell phero__inner">
    <p class="crumbs">
      <a href="index.php">Home</a> &nbsp;/&nbsp; Contact
    </p>

    <span class="eyebrow" style="margin-top:1.2rem">Contact</span>

    <h1>Reach the Palace Court</h1>

    <p class="lede">
      For enquiries on heritage, visits, media, and the 10th Coronation Anniversary celebration.
    </p>
  </div>
</section>

<section class="section">
  <div class="shell split">

    <div class="split__body reveal">
      <span class="eyebrow">Send a Message</span>

      <h2 style="margin:1rem 0 1.2rem">Write to the Palace</h2>

      <div class="rule"></div>

      <form class="form" method="POST" action="https://api.web3forms.com/submit">

        <!-- Web3Forms Access Key -->
        <input type="hidden" name="access_key" value="a0a54b16-f189-4c4d-9a63-0bfcb9574e5b">

        <!-- Email subject -->
        <input type="hidden" name="subject" value="New Contact Enquiry — Royal Palace of Benin">

        <!-- Name shown as sender -->
        <input type="hidden" name="from_name" value="Royal Palace of Benin Website">

        <!-- Spam protection -->
        <input type="checkbox" name="botcheck" class="hidden" style="display:none">

        <div class="field">
          <label for="name">Full Name</label>
          <input id="name" name="name" type="text" required>
        </div>

        <div class="field">
          <label for="email">Email Address</label>
          <input id="email" name="email" type="email" required>
        </div>

        <div class="field">
          <label for="message_subject">Subject</label>
          <input id="message_subject" name="message_subject" type="text" placeholder="What is your enquiry about?">
        </div>

        <div class="field">
          <label for="message">Message</label>
          <textarea id="message" name="message" rows="5" required></textarea>
        </div>

        <div class="btn-row">
          <button class="btn btn--royal" type="submit">
            Send Message
          </button>
        </div>

      </form>
    </div>

    <div class="split__body reveal" data-d="1">

      <div class="card" style="padding:2.2rem">
        <span class="eyebrow">The Palace</span>

        <h3 style="margin:1rem 0 1rem">
          Royal Palace of Benin
        </h3>

        <ul class="infolist">
          <li>
            <span>Benin City, Edo State, Nigeria</span>
          </li>

          <li>
            <span>info@beninroyalcourt.com</span>
          </li>

          <li>
            <span>+234 (0) XXX XXXX XXX</span>
          </li>
        </ul>
      </div>

      <div class="card" style="padding:2.2rem;margin-top:1.5rem">

        <span class="eyebrow">Anniversary Venues</span>

        <h3 style="margin:1rem 0 1rem">
          10th Coronation Anniversary
        </h3>

        <ul class="infolist">
          <li>
            <span>Multiple Venues, Benin City</span>
          </li>

          <li>
            <span>Samuel Ogbemudia Stadium</span>
          </li>

          <li>
            <span>Oba Akenzua Cultural Centre</span>
          </li>

          <li>
            <span>University of Benin</span>
          </li>

          <li>
            <span>Holy Aruosa Cathedral</span>
          </li>
        </ul>

        <p style="margin-top:1.4rem">
          <a class="textlink" href="anniversary.php#rsvp">
            RSVP for the Anniversary
          </a>
        </p>

      </div>

    </div>

  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>