# The Royal Palace of Benin — PHP + MySQL Website

This is your original HTML/CSS/JS design, converted into a dynamic PHP 8 + MySQL
website with a secure admin dashboard for managing News, Gallery, and Contact
messages. No framework is used — plain PHP with PDO, so it runs on any
standard cPanel/Hostinger-style PHP hosting.

## ⚠️ Before you deploy: your images are missing

Your uploaded `palace.zip` did not contain any files inside `assets/images/`
— the HTML referenced images like `hero-palace.jpg`, `throne-room.jpg`, etc.,
but the folder was empty. Copy your actual image files into
`assets/images/` (same filenames the pages reference) before going live, or
those spots will show broken images.

## 1. Requirements

- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- `pdo_mysql`, `fileinfo`, and `gd` (or equivalent) PHP extensions — these are
  enabled by default on virtually all shared hosting.

## 2. Database setup

1. In cPanel/phpMyAdmin, create a new MySQL database and a database user with
   full privileges on it.
2. Import `database.sql` into that database (phpMyAdmin → Import, or
   `mysql -u USER -p DBNAME < database.sql`).

## 3. Configuration

Open `includes/config.php` and set your real database credentials, or
(preferred, if your host supports it) set these as environment variables:

```
DB_HOST=localhost
DB_NAME=your_db_name
DB_USER=your_db_user
DB_PASS=your_db_password
SITE_URL=https://yourdomain.com
```

If you can't set environment variables on your host, just edit the
`define(...)` values directly in `includes/config.php`.

## 4. Create your admin account

No password is hard-coded anywhere in this project. Create your first admin
user from the command line (SSH, or your host's "Terminal" feature in
cPanel):

```
php admin/create-admin.php your_username your_email@example.com "YourStrongPassword123"
```

You can run this again any time to add more admin users. Then log in at:

```
https://yourdomain.com/admin/login.php
```

## 5. Upload to your host

Upload everything to your domain's web root (e.g. `public_html/`). The whole
project is designed to sit at the root of a domain or subdomain — if you
must deploy into a subfolder, you'll need to adjust the root-relative
`/admin/...` and `/assets/...` links in `includes/admin_header.php`,
`includes/header.php`/`footer.php`, and the admin pages.

Make sure `uploads/news/` and `uploads/gallery/` are writable by PHP
(755 or 775 depending on your host).

## 6. What's dynamic vs. static

- **News** (`news.php`, `article.php`) — fully dynamic from the `news` table,
  manageable at `/admin/news/`. Falls back to the original 3 sample cards on
  the homepage if the database is empty, so the site never looks broken.
- **Gallery** (`gallery.php`) — fully dynamic from the `gallery` table,
  manageable at `/admin/gallery/`. I added a lightweight click-to-enlarge
  lightbox and category filter buttons (your original had neither) — these
  are additive only; nothing in your existing CSS/JS was overwritten.
- **Contact form + Anniversary RSVP form** — both now really submit, via
  `contact-submit.php`, into the `messages` table, manageable at
  `/admin/messages/`. Same "thank you" UX as your original static form.
- **Home, About, Heritage, Council, Anniversary pages** — same design,
  just converted to `.php` with shared header/footer includes. The homepage's
  "Latest News" and "Gallery Preview" sections now also pull from the
  database.

## 7. Admin panel

`/admin/login.php` — sidebar navigation for Dashboard, News (all/add),
Gallery (all/upload), Messages, and Settings (change email/password).
Session-based auth, CSRF-protected forms, `password_hash()`/`password_verify()`,
30-minute idle timeout.

## 8. Security notes

- All database queries use PDO prepared statements.
- All forms are CSRF-protected.
- Uploaded files are validated by real MIME type (not filename/extension),
  renamed to random filenames, and the `uploads/` folder has a `.htaccess`
  that blocks any PHP execution inside it — even if a malicious file ever
  got through.
- `includes/` is blocked from direct web access via `.htaccess`.
- Set `APP_DEBUG=0` (or leave the env var unset) in production — this is
  already the default — so PHP errors aren't shown to visitors.

## 9. Optional: pretty news URLs

The root `.htaccess` includes a rewrite rule so `/news/some-slug` also works
as an alternative to `/article.php?slug=some-slug` (both work — internal
links currently use the `article.php?slug=` form for maximum host
compatibility, since not all budget hosts have `mod_rewrite` enabled).
