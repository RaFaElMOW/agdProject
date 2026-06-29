<?php
  require_once __DIR__ . '/includes/cms-bootstrap.php';

  use App\Repositories\ProjectRepository;

  $slug = isset($_GET['slug']) ? (string) $_GET['slug'] : '';
  $project = $slug !== '' ? (new ProjectRepository())->findBySlug($slug) : null;

  if ($project === null) {
      header('Location: projetos.php');
      exit;
  }

  $gallery = (new ProjectRepository())->galleryFor((int) $project['id']);

  $pageTitle = $project['name'];
  $activePage = 'projetos';
  include 'includes/partials/header.php';
?>

    <div class="hero-wrap" style="background-image: url('<?php echo e(public_asset_url($project['banner'] ?: 'images/bg_5.jpg')); ?>');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
             <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php"><?php echo t('Home'); ?></a></span><span class="mr-2"><a href="projetos.php"><?php echo t('Projects'); ?></a></span> <span><?php echo e($project['name']); ?></span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo e($project['name']); ?></h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-9 ftco-animate">
            <?php if (!empty($project['description'])): ?>
            <p style="white-space: pre-line;"><?php echo e($project['description']); ?></p>
            <?php endif; ?>
            <?php if (!empty($project['external_link'])): ?>
            <p><a href="<?php echo e($project['external_link']); ?>" target="_blank" rel="noopener" class="btn btn-primary px-4 py-2"><?php echo t('Learn More Link'); ?> <i class="ion-ios-arrow-forward"></i></a></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>

    <?php if ($gallery !== []): ?>
    <section class="ftco-gallery">
      <div class="d-md-flex">
        <?php foreach ($gallery as $img): ?>
        <a href="<?php echo e(public_asset_url($img['image_path'])); ?>" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(<?php echo e(public_asset_url($img['image_path'])); ?>);">
          <div class="icon d-flex justify-content-center align-items-center"><span class="icon-search"></span></div>
        </a>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>

    <section class="ftco-section bg-light">
      <div class="container">
        <div class="row justify-content-center text-center">
          <div class="col-md-8 ftco-animate">
            <p><a href="projetos.php" class="btn btn-secondary px-4 py-2">&laquo; <?php echo t('Projects'); ?></a>
            <a href="apadrinhar.php" class="btn btn-primary px-4 py-2"><?php echo t('Sponsor'); ?></a></p>
          </div>
        </div>
      </div>
    </section>

<?php include 'includes/partials/footer.php'; ?>
