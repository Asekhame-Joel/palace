<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$errors = [];
$values = [
    'title'        => '',
    'excerpt'      => '',
    'content'      => '',
    'category'     => '',
    'author'       => '',
    'status'       => 'draft',
    'published_at' => '',
];

$categories = db()->query("SELECT DISTINCT category FROM news WHERE category IS NOT NULL AND category != '' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();

    $values['title']        = trim((string) ($_POST['title'] ?? ''));
    $values['excerpt']      = trim((string) ($_POST['excerpt'] ?? ''));
    $values['content']      = trim((string) ($_POST['content'] ?? ''));
    $values['category']     = trim((string) ($_POST['category'] ?? ''));
    $values['author']       = trim((string) ($_POST['author'] ?? ''));
    $values['status']       = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
    $values['published_at'] = trim((string) ($_POST['published_at'] ?? ''));

    if ($values['title'] === '' || mb_strlen($values['title']) > 255) {
        $errors[] = 'Please enter a title (up to 255 characters).';
    }
    if ($values['content'] === '') {
        $errors[] = 'Please enter the article content.';
    }
    if ($values['excerpt'] !== '' && mb_strlen($values['excerpt']) > 500) {
        $errors[] = 'The excerpt must be under 500 characters.';
    }

    $featuredImage = null;
    if (!empty($_FILES['featured_image']['name'])) {
        try {
            $featuredImage = handle_image_upload($_FILES['featured_image'], UPLOADS_NEWS_PATH);
        } catch (RuntimeException $ex) {
            $errors[] = $ex->getMessage();
        }
    }

    if (empty($errors)) {
        $publishedAt = null;
        if ($values['status'] === 'published') {
            $publishedAt = $values['published_at'] !== ''
                ? date('Y-m-d H:i:s', strtotime($values['published_at']))
                : date('Y-m-d H:i:s');
        } elseif ($values['published_at'] !== '') {
            $publishedAt = date('Y-m-d H:i:s', strtotime($values['published_at']));
        }

        $slug = make_unique_slug($values['title'], 'news');
        $safeContent = strip_tags($values['content'], '<p><br><strong><b><em><i><ul><ol><li><a><h2><h3><h4><blockquote><img>');

        $stmt = db()->prepare('INSERT INTO news (title, slug, excerpt, content, featured_image, category, author, status, published_at, created_at, updated_at)
                                VALUES (:title, :slug, :excerpt, :content, :image, :category, :author, :status, :published_at, NOW(), NOW())');
        $stmt->execute([
            'title'        => $values['title'],
            'slug'         => $slug,
            'excerpt'      => $values['excerpt'] !== '' ? $values['excerpt'] : null,
            'content'      => $safeContent,
            'image'        => $featuredImage,
            'category'     => $values['category'] !== '' ? $values['category'] : null,
            'author'       => $values['author'] !== '' ? $values['author'] : null,
            'status'       => $values['status'],
            'published_at' => $publishedAt,
        ]);

        flash_set('success', 'News post created successfully.');
        redirect('/admin/news/index.php');
    }
}

$adminPageTitle = 'Add News';
$activeAdminNav = 'news-add';
require __DIR__ . '/../../includes/admin_header.php';
?>
        <div class="a-card">
<?php foreach ($errors as $err): ?>
          <div class="a-alert error"><?php echo e($err); ?></div>
<?php endforeach; ?>
          <form method="post" action="/admin/news/create.php" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="a-field">
              <label for="title">Title</label>
              <input type="text" id="title" name="title" value="<?php echo e($values['title']); ?>" required maxlength="255">
            </div>
            <div class="a-field">
              <label for="excerpt">Excerpt <span style="font-weight:400;color:var(--a-ink-60)">(optional — shown on listing cards)</span></label>
              <textarea id="excerpt" name="excerpt" rows="2" maxlength="500"><?php echo e($values['excerpt']); ?></textarea>
            </div>
            <div class="a-field">
              <label for="content">Article Content</label>
              <textarea id="content" name="content" rows="12" required><?php echo e($values['content']); ?></textarea>
              <p class="hint">Basic HTML tags are supported: &lt;p&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;ul&gt;/&lt;li&gt;, &lt;h2&gt;-&lt;h4&gt;, &lt;a&gt;, &lt;img&gt;, &lt;blockquote&gt;.</p>
            </div>
            <div class="a-form-grid">
              <div class="a-field">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" value="<?php echo e($values['category']); ?>" list="category-list" placeholder="e.g. Anniversary, Heritage Preservation">
                <datalist id="category-list">
<?php foreach ($categories as $cat): ?>
                  <option value="<?php echo e($cat); ?>">
<?php endforeach; ?>
                </datalist>
              </div>
              <div class="a-field">
                <label for="author">Author <span style="font-weight:400;color:var(--a-ink-60)">(optional)</span></label>
                <input type="text" id="author" name="author" value="<?php echo e($values['author']); ?>">
              </div>
              <div class="a-field">
                <label for="status">Status</label>
                <select id="status" name="status">
                  <option value="draft" <?php echo $values['status'] === 'draft' ? 'selected' : ''; ?>>Save as Draft</option>
                  <option value="published" <?php echo $values['status'] === 'published' ? 'selected' : ''; ?>>Publish</option>
                </select>
              </div>
              <div class="a-field">
                <label for="published_at">Publication Date <span style="font-weight:400;color:var(--a-ink-60)">(optional — defaults to now)</span></label>
                <input type="datetime-local" id="published_at" name="published_at" value="<?php echo e($values['published_at']); ?>">
              </div>
            </div>
            <div class="a-field">
              <label for="featured_image">Featured Image <span style="font-weight:400;color:var(--a-ink-60)">(optional — JPG, PNG, WEBP, or GIF, up to 5MB)</span></label>
              <input type="file" id="featured_image" name="featured_image" accept="image/jpeg,image/png,image/webp,image/gif">
            </div>
            <button class="a-btn" type="submit">Create News Post</button>
            <a class="a-btn outline" href="/admin/news/index.php">Cancel</a>
          </form>
        </div>
<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
