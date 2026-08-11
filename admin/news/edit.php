<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id <= 0) {
    redirect('/admin/news/index.php');
}

$stmt = db()->prepare('SELECT * FROM news WHERE id = :id');
$stmt->execute(['id' => $id]);
$post = $stmt->fetch();

if (!$post) {
    flash_set('error', 'News post not found.');
    redirect('/admin/news/index.php');
}

$errors = [];
$values = [
    'title'        => $post['title'],
    'excerpt'      => $post['excerpt'] ?? '',
    'content'      => $post['content'],
    'category'     => $post['category'] ?? '',
    'author'       => $post['author'] ?? '',
    'status'       => $post['status'],
    'published_at' => $post['published_at'] ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : '',
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
    $removeImage             = !empty($_POST['remove_image']);

    if ($values['title'] === '' || mb_strlen($values['title']) > 255) {
        $errors[] = 'Please enter a title (up to 255 characters).';
    }
    if ($values['content'] === '') {
        $errors[] = 'Please enter the article content.';
    }
    if ($values['excerpt'] !== '' && mb_strlen($values['excerpt']) > 500) {
        $errors[] = 'The excerpt must be under 500 characters.';
    }

    $newImage = null;
    if (!empty($_FILES['featured_image']['name'])) {
        try {
            $newImage = handle_image_upload($_FILES['featured_image'], UPLOADS_NEWS_PATH);
        } catch (RuntimeException $ex) {
            $errors[] = $ex->getMessage();
        }
    }

    if (empty($errors)) {
        $publishedAt = $post['published_at'];
        if ($values['status'] === 'published' && !$publishedAt) {
            $publishedAt = date('Y-m-d H:i:s');
        }
        if ($values['published_at'] !== '') {
            $publishedAt = date('Y-m-d H:i:s', strtotime($values['published_at']));
        }

        $slug = $post['slug'];
        if ($values['title'] !== $post['title']) {
            $slug = make_unique_slug($values['title'], 'news', $id);
        }

        $safeContent = strip_tags($values['content'], '<p><br><strong><b><em><i><ul><ol><li><a><h2><h3><h4><blockquote><img>');

        $finalImage = $post['featured_image'];
        if ($newImage) {
            delete_upload(UPLOADS_NEWS_PATH, $post['featured_image']);
            $finalImage = $newImage;
        } elseif ($removeImage) {
            delete_upload(UPLOADS_NEWS_PATH, $post['featured_image']);
            $finalImage = null;
        }

        $upd = db()->prepare('UPDATE news SET title=:title, slug=:slug, excerpt=:excerpt, content=:content,
                               featured_image=:image, category=:category, author=:author, status=:status,
                               published_at=:published_at, updated_at=NOW() WHERE id=:id');
        $upd->execute([
            'title'        => $values['title'],
            'slug'         => $slug,
            'excerpt'      => $values['excerpt'] !== '' ? $values['excerpt'] : null,
            'content'      => $safeContent,
            'image'        => $finalImage,
            'category'     => $values['category'] !== '' ? $values['category'] : null,
            'author'       => $values['author'] !== '' ? $values['author'] : null,
            'status'       => $values['status'],
            'published_at' => $publishedAt,
            'id'           => $id,
        ]);

        flash_set('success', 'News post updated successfully.');
        redirect('/admin/news/index.php');
    }
}

$adminPageTitle = 'Edit News';
$activeAdminNav = 'news';
require __DIR__ . '/../../includes/admin_header.php';
?>
        <div class="a-card">
<?php foreach ($errors as $err): ?>
          <div class="a-alert error"><?php echo e($err); ?></div>
<?php endforeach; ?>
          <form method="post" action="/admin/news/edit.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
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
                <input type="text" id="category" name="category" value="<?php echo e($values['category']); ?>" list="category-list">
                <datalist id="category-list">
<?php foreach ($categories as $cat): ?>
                  <option value="<?php echo e($cat); ?>">
<?php endforeach; ?>
                </datalist>
              </div>
              <div class="a-field">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" value="<?php echo e($values['author']); ?>">
              </div>
              <div class="a-field">
                <label for="status">Status</label>
                <select id="status" name="status">
                  <option value="draft" <?php echo $values['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                  <option value="published" <?php echo $values['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                </select>
              </div>
              <div class="a-field">
                <label for="published_at">Publication Date</label>
                <input type="datetime-local" id="published_at" name="published_at" value="<?php echo e($values['published_at']); ?>">
              </div>
            </div>
            <div class="a-field">
              <label>Featured Image</label>
<?php if ($post['featured_image']): ?>
              <div class="a-current-image">
                <img src="/<?php echo e(UPLOADS_NEWS_URL . '/' . $post['featured_image']); ?>" alt="">
                <label style="font-weight:400;display:flex;align-items:center;gap:.4rem">
                  <input type="checkbox" name="remove_image" value="1" style="width:auto"> Remove current image
                </label>
              </div>
<?php endif; ?>
              <input type="file" id="featured_image" name="featured_image" accept="image/jpeg,image/png,image/webp,image/gif">
              <p class="hint">Upload a new image to replace the current one. Max 5MB.</p>
            </div>
            <button class="a-btn" type="submit">Save Changes</button>
            <a class="a-btn outline" href="/admin/news/index.php">Cancel</a>
          </form>
        </div>
<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
