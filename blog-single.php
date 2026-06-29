<?php
  require_once __DIR__ . '/includes/cms-bootstrap.php';

  use App\Repositories\BlogCommentRepository;
  use App\Repositories\BlogPostRepository;
  use App\Repositories\BlogTagRepository;
  use App\Repositories\BlogCategoryRepository;
  use App\Services\RateLimiterService;

  $slug = isset($_GET['slug']) ? (string) $_GET['slug'] : '';
  $postRepository = new BlogPostRepository();
  $post = $slug !== '' ? $postRepository->findPublishedBySlug($slug) : null;

  if ($post === null) {
      header('Location: blog.php');
      exit;
  }

  $commentStatus = null;

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_submit'])) {
      $honeypot = (string) ($_POST['website'] ?? '');
      $name = trim((string) ($_POST['name'] ?? ''));
      $email = trim((string) ($_POST['email'] ?? ''));
      $message = trim((string) ($_POST['message'] ?? ''));

      $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
      $allowed = (new RateLimiterService())->attempt('blog-comment:' . $ip, 5, 600);

      if ($honeypot !== '') {
          // Silently drop bot submissions (filled hidden field) without revealing the trap.
          $commentStatus = 'sent';
      } elseif (!$allowed) {
          $commentStatus = 'rate_limited';
      } elseif ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
          $commentStatus = 'invalid';
      } else {
          (new BlogCommentRepository())->create([
              'post_id' => (int) $post['id'],
              'parent_id' => null,
              'author_name' => $name,
              'author_email' => $email,
              'content' => $message,
              'ip' => $ip,
          ]);
          $commentStatus = 'sent';
      }

      $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: 'blog-single.php';
      header('Location: ' . $currentPath . '?comment=' . $commentStatus . '#comments');
      exit;
  }

  $commentStatus = isset($_GET['comment']) ? (string) $_GET['comment'] : null;
  $tags = (new BlogTagRepository())->namesForPost((int) $post['id']);
  $comments = (new BlogCommentRepository())->approvedForPost((int) $post['id']);
  $recentPosts = $postRepository->recentPublished(3, (int) $post['id']);
  $categories = (new BlogCategoryRepository())->all();
  $allTags = (new BlogTagRepository())->all();

  $pageTitle = $post['title'];
  $activePage = 'blog';
  include 'includes/partials/header.php';
?>

    <div class="hero-wrap" style="background-image: url('<?php echo e(public_asset_url($post['banner'] ?: 'images/bg_2.jpg')); ?>');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
             <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php"><?php echo t('Home'); ?></a></span> <span class="mr-2"><a href="blog.php"><?php echo t('Blog'); ?></a></span> <span><?php echo e($post['title']); ?></span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo e($post['title']); ?></h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section ftco-degree-bg">
      <div class="container">
        <div class="row">
          <div class="col-md-8 ftco-animate">
            <div class="meta mb-4">
              <div><span class="icon-calendar mr-1"></span> <?php echo e(date('d/m/Y', strtotime((string) $post['published_at']))); ?></div>
              <?php if (!empty($post['category_name'])): ?><div><span class="icon-bookmark mr-1"></span> <?php echo e($post['category_name']); ?></div><?php endif; ?>
            </div>

            <?php echo $post['content'] ?: ''; /* already sanitized via HTMLPurifier on save */ ?>

            <?php if ($tags !== []): ?>
            <div class="tag-widget post-tag-container mb-5 mt-5">
              <div class="tagcloud">
                <?php foreach ($tags as $tagName): ?>
                  <span class="tag-cloud-link"><?php echo e($tagName); ?></span>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>

            <div class="pt-5 mt-5" id="comments">
              <h3 class="mb-5"><?php echo count($comments); ?> <?php echo t('Reply'); ?></h3>
              <?php if ($comments !== []): ?>
              <ul class="comment-list">
                <?php foreach ($comments as $c): ?>
                <li class="comment">
                  <div class="comment-body">
                    <h3><?php echo e($c['author_name']); ?></h3>
                    <div class="meta"><?php echo e(date('d/m/Y H:i', strtotime($c['created_at']))); ?></div>
                    <p><?php echo e($c['content']); ?></p>
                  </div>
                </li>
                <?php endforeach; ?>
              </ul>
              <?php endif; ?>

              <div class="comment-form-wrap pt-5">
                <h3 class="mb-5"><?php echo t('Leave a comment'); ?></h3>
                <?php if ($commentStatus === 'sent'): ?>
                  <div class="alert alert-success">Comentário enviado! Ele será exibido após moderação.</div>
                <?php elseif ($commentStatus === 'invalid'): ?>
                  <div class="alert alert-danger">Preencha nome, e-mail válido e mensagem.</div>
                <?php elseif ($commentStatus === 'rate_limited'): ?>
                  <div class="alert alert-danger">Muitos comentários enviados. Tente novamente em alguns minutos.</div>
                <?php endif; ?>
                <form method="post" action="" class="p-5 bg-light">
                  <div style="position:absolute; left:-9999px;" aria-hidden="true">
                    <label for="website">Website</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="name"><?php echo t('Name *'); ?></label>
                    <input type="text" class="form-control" id="name" name="name" required>
                  </div>
                  <div class="form-group">
                    <label for="email"><?php echo t('Email *'); ?></label>
                    <input type="email" class="form-control" id="email" name="email" required>
                  </div>
                  <div class="form-group">
                    <label for="message"><?php echo t('Message'); ?></label>
                    <textarea id="message" name="message" cols="30" rows="10" class="form-control" required></textarea>
                  </div>
                  <div class="form-group">
                    <input type="submit" name="comment_submit" value="<?php echo t('Post Comment'); ?>" class="btn py-3 px-4 btn-primary">
                  </div>
                </form>
              </div>
            </div>

          </div> <!-- .col-md-8 -->
          <div class="col-md-4 sidebar ftco-animate">
            <?php if ($categories !== []): ?>
            <div class="sidebar-box ftco-animate">
              <div class="categories">
                <h3><?php echo t('Categories'); ?></h3>
                <?php foreach ($categories as $cat): ?>
                <li><?php echo e($cat['name']); ?></li>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>

            <?php if ($recentPosts !== []): ?>
            <div class="sidebar-box ftco-animate">
              <h3><?php echo t('Recent Blog'); ?></h3>
              <?php foreach ($recentPosts as $rp): ?>
              <div class="block-21 mb-4 d-flex">
                <a class="blog-img mr-4" href="<?php echo e('blog/' . $rp['slug']); ?>" style="background-image: url(<?php echo e(public_asset_url($rp['banner'] ?: 'images/image_1.jpg')); ?>);"></a>
                <div class="text">
                  <h3 class="heading"><a href="<?php echo e('blog/' . $rp['slug']); ?>"><?php echo e($rp['title']); ?></a></h3>
                  <div class="meta">
                    <div><span class="icon-calendar"></span> <?php echo e(date('d/m/Y', strtotime($rp['published_at']))); ?></div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if ($allTags !== []): ?>
            <div class="sidebar-box ftco-animate">
              <h3><?php echo t('Tag Cloud'); ?></h3>
              <div class="tagcloud">
                <?php foreach ($allTags as $t): ?>
                  <span class="tag-cloud-link"><?php echo e($t['name']); ?></span>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>
          </div>

        </div>
      </div>
    </section> <!-- .section -->


<?php include 'includes/partials/footer.php'; ?>
