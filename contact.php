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

    <div class="split__body contact-side reveal" data-d="1">

      <div class="card contact-card">
        <span class="eyebrow">The Palace</span>

        <h3>Royal Palace of Benin</h3>

        <ul class="infolist">
          <li>
            <span>Oba Palace, Kings Square, Benin City</span>
          </li>

          <li>
            <span>info@beninroyalcourt.com</span>
          </li>
        </ul>
      </div>

      <div class="card contact-card">
        <span class="eyebrow">Enquiries</span>

        <div class="contact-people">
          <div class="contact-person">
            <h4>His Excellency, Hon. Lucky Imasuen</h4>
            <h6 class="contact-person__role">Chairman</h6>
            <h6><a href="mailto:eghosa.imasuen@yahoo.com">eghosa.imasuen@yahoo.com</a></h6>
            <h6><a href="tel:+2348030409997">+234 803 040 9997</a></h6>
          </div>

          <div class="contact-person">
            <h4>Osarenogae Hillary Igbinadolor</h4>
            <h6 class="contact-person__role">Committee Secretary / SA to HRM</h6>
            <h6><a href="mailto:hillarybarkley@gmail.com">hillarybarkley@gmail.com</a></h6>
            <h6><a href="tel:+2348102462567">+234 810 246 2567</a></h6>
          </div>

          <div class="contact-person">
            <h4>Mr. Frank Irabor</h4>
            <h6 class="contact-person__role">Secretary, Benin Traditional Council</h6>
            <h6><a href="mailto:iraborfrank@gmail.com">iraborfrank@gmail.com</a></h6>
            <h6><a href="tel:+2348131162616">+234 813 116 2616</a></h6>
          </div>
        </div>
      </div>

      <div class="card contact-card">

        <span class="eyebrow">Anniversary Venues</span>

        <h3>10th Coronation Anniversary</h3>

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