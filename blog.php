<?php
  $pageTitle = 'Blog';
  $activePage = 'blog';

  require_once __DIR__ . '/includes/cms-bootstrap.php';
  $blogPage = max(1, (int) ($_GET['page'] ?? 1));
  $blogResult = (new \App\Repositories\BlogPostRepository())->publishedPaginated($blogPage, 9);
  $blogPosts = $blogResult['rows'];
  $blogTotalPages = (int) ceil($blogResult['total'] / 9) ?: 1;

  include 'includes/partials/header.php';
?>

    <div class="hero-wrap" style="background-image: url('images/bg_2.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
             <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php"><?php echo t('Home'); ?></a></span> <span><?php echo t('Blog'); ?></span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo t('Blog'); ?></h1>
          </div>
        </div>
      </div>
    </div>


    <section class="ftco-section">
      <div class="container">
        <?php if ($blogPosts !== []): ?>
        <div class="row d-flex">
          <?php foreach ($blogPosts as $post): ?>
          <div class="col-md-4 d-flex ftco-animate">
            <div class="blog-entry align-self-stretch">
              <a href="<?php echo e('blog/' . $post['slug']); ?>" class="block-20" style="background-image: url('<?php echo e(public_asset_url($post['banner'] ?: 'images/news_1.jpg')); ?>');">
              </a>
              <div class="text p-4 d-block">
                <div class="meta mb-3">
                  <div><span class="icon-calendar mr-1"></span> <?php echo e(date('d/m/Y', strtotime($post['published_at']))); ?></div>
                  <?php if (!empty($post['category_name'])): ?><div><span class="icon-bookmark mr-1"></span> <?php echo e($post['category_name']); ?></div><?php endif; ?>
                </div>
                <h3 class="heading mt-3"><a href="<?php echo e('blog/' . $post['slug']); ?>"><?php echo e($post['title']); ?></a></h3>
                <?php if (!empty($post['excerpt'])): ?><p><?php echo e(mb_strimwidth($post['excerpt'], 0, 120, '...')); ?></p><?php endif; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php if ($blogTotalPages > 1): ?>
        <div class="row justify-content-center mt-3">
          <div class="col-md-12 text-center">
            <?php if ($blogPage > 1): ?><a href="?page=<?php echo $blogPage - 1; ?>" class="btn btn-secondary px-3 py-2">&laquo;</a><?php endif; ?>
            <span class="px-2">Página <?php echo $blogPage; ?> de <?php echo $blogTotalPages; ?></span>
            <?php if ($blogPage < $blogTotalPages): ?><a href="?page=<?php echo $blogPage + 1; ?>" class="btn btn-secondary px-3 py-2">&raquo;</a><?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <div class="row d-flex">
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="blog-single.php" class="block-20" style="background-image: url('images/news_1.jpg');">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-calendar mr-1"></span> <?php echo t('Date Apr 8'); ?></div>
                  <div><span class="icon-person mr-1"></span> Giovana Canhoni</div>
                  <div><span class="icon-clock-o mr-1"></span> <?php echo t('Reading Time 1 Min'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="blog-single.php"><?php echo t('Article Quem Somos Title'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="blog-single.php" class="block-20" style="background-image: url('images/news_2.jpg');">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-calendar mr-1"></span> <?php echo t('Date Apr 5'); ?></div>
                  <div><span class="icon-person mr-1"></span> Giovana Canhoni</div>
                  <div><span class="icon-clock-o mr-1"></span> <?php echo t('Reading Time 1 Min'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="blog-single.php"><?php echo t('Article Amor Transforma Title'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="blog-single.php" class="block-20" style="background-image: url('images/news_3.jpg');">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-calendar mr-1"></span> <?php echo t('Date May 20 2025'); ?></div>
                  <div><span class="icon-person mr-1"></span> Giovana Canhoni</div>
                  <div><span class="icon-clock-o mr-1"></span> <?php echo t('Reading Time 2 Min'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="blog-single.php"><?php echo t('Article Relatorio Niger Title'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="blog-single.php" class="block-20" style="background-image: url('images/news_4.jpg');">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-calendar mr-1"></span> <?php echo t('Date Mar 28 2025'); ?></div>
                  <div><span class="icon-person mr-1"></span> Giovana Canhoni</div>
                  <div><span class="icon-clock-o mr-1"></span> <?php echo t('Reading Time 1 Min'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="blog-single.php"><?php echo t('Article Pastor Sequestrado Title'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="blog-single.php" class="block-20" style="background-image: url('images/livro_deserto_banner.png');">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-calendar mr-1"></span> <?php echo t('Date Mar 7 2025'); ?></div>
                  <div><span class="icon-person mr-1"></span> Giovana Canhoni</div>
                  <div><span class="icon-clock-o mr-1"></span> <?php echo t('Reading Time 2 Min'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="blog-single.php"><?php echo t('Book Deserto Title'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="blog-single.php" class="block-20" style="background-image: url('images/news_6.jpg');">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-calendar mr-1"></span> <?php echo t('Date Dec 14 2024'); ?></div>
                  <div><span class="icon-person mr-1"></span> Giovana Canhoni</div>
                  <div><span class="icon-clock-o mr-1"></span> <?php echo t('Reading Time 1 Min'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="blog-single.php"><?php echo t('Article Apresentacao Projeto Title'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="https://www.youtube.com/watch?v=gRUokNg-Bt4" class="block-20 popup-youtube" style="background-image: url('images/news_7.jpg');">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-calendar mr-1"></span> <?php echo t('Date Oct 4 2024'); ?></div>
                  <div><span class="icon-person mr-1"></span> Giovana Canhoni</div>
                  <div><span class="icon-clock-o mr-1"></span> <?php echo t('Reading Time 1 Min'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="https://www.youtube.com/watch?v=gRUokNg-Bt4" class="popup-youtube"><?php echo t('Article Nunca Sozinhos Title'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="blog-single.php" class="block-20" style="background-image: url('images/news_8.jpg');">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-calendar mr-1"></span> <?php echo t('Date Sep 6 2024'); ?></div>
                  <div><span class="icon-person mr-1"></span> Giovana Canhoni</div>
                  <div><span class="icon-clock-o mr-1"></span> <?php echo t('Reading Time 1 Min'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="blog-single.php"><?php echo t('Article Apadrinhamento Escolar Title'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="blog-single.php" class="block-20" style="background-image: url('images/news_9.jpg');">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-calendar mr-1"></span> <?php echo t('Date Feb 6 2024'); ?></div>
                  <div><span class="icon-person mr-1"></span> Giovana Canhoni</div>
                  <div><span class="icon-clock-o mr-1"></span> <?php echo t('Reading Time 1 Min'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="blog-single.php"><?php echo t('Article Horta Deserto Title'); ?></a></h3>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </section>


<?php include 'includes/partials/footer.php'; ?>
