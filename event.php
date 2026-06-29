<?php
  $pageTitle = 'Books';
  $activePage = 'event';

  require_once __DIR__ . '/includes/cms-bootstrap.php';
  $booksList = (new \App\Repositories\BookRepository())->activeOrdered();

  include 'includes/partials/header.php';
?>

    <div class="hero-wrap" style="background-image: url('images/bg_1.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
             <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php"><?php echo t('Home'); ?></a></span> <span><?php echo t('Books'); ?></span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo t('Books'); ?></h1>
          </div>
        </div>
      </div>
    </div>


    <section class="ftco-section">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4"><?php echo t('Books Heading'); ?></h2>
            <p><?php echo t('Books Intro Text'); ?></p>
          </div>
        </div>
        <div class="row mb-5">
          <div class="col-12 ftco-animate">
        	<div class="launch-banner">
        		<span class="launch-badge"><?php echo t('Launch Badge'); ?></span>
        		<a href="https://www.amazon.com/Uma-Vida-no-Deserto-Portuguese-ebook/dp/B0DZR1D8MP/" target="_blank" rel="noopener" class="banner-img d-block" style="background-image: url(images/livro_deserto_banner.png);"></a>
        		<div class="launch-cta">
        			<a href="https://www.amazon.com/Uma-Vida-no-Deserto-Portuguese-ebook/dp/B0DZR1D8MP/" target="_blank" rel="noopener" class="btn btn-primary px-4 py-3"><span class="icon-amazon mr-2"></span><?php echo t('See On Amazon Button'); ?></a>
        		</div>
        	</div>
          </div>
        </div>
        <?php if ($booksList !== []): ?>
        <div class="row">
          <?php foreach ($booksList as $book): ?>
            <?php echo \App\Core\View::render('partials/book-card', ['book' => $book]); ?>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="row">
        	<div class="col-md-6 mb-4 ftco-animate">
        		<div class="staff sponsor-card text-center">
        			<a href="mailto:giovanacanhoni@icloud.com?subject=Quero%20comprar%20o%20livro%20O%20Sofrimento%20da%20Alma%20e%20o%20Socorro%20de%20Deus" class="block-20 book-cover d-block" style="background-image: url(images/livro_sofrimento_alma.jpg); margin: -25px -25px 20px -25px; border-radius: 4px 4px 0 0;"></a>
        			<h3 class="mb-1"><?php echo t('Book Sofrimento Title'); ?></h3>
        			<p class="mb-2" style="color:#7cbd1e;font-weight:500;">Giovana Canhoni</p>
        			<p class="mb-3">
        				<span class="badge badge-pill mr-1 mb-1" style="background:#fff6f2;color:#f86f2d;padding:8px 14px;"><?php echo t('Physical Price Badge'); ?></span>
        				<span class="badge badge-pill mb-1" style="background:#fff6f2;color:#f86f2d;padding:8px 14px;"><?php echo t('Ebook Price Badge'); ?></span>
        			</p>
        			<p><?php echo t('Book Sofrimento Text'); ?></p>
        			<p class="mb-0">
        				<a href="mailto:giovanacanhoni@icloud.com?subject=Quero%20comprar%20o%20livro%20O%20Sofrimento%20da%20Alma%20e%20o%20Socorro%20de%20Deus" class="btn btn-primary px-4 py-3 mr-2 mb-2"><?php echo t('I Want To Buy Button'); ?></a>
        				<a href="https://wa.me/5511965714533" target="_blank" rel="noopener" class="btn btn-outline-primary px-4 py-3 mb-2"><span class="icon-whatsapp mr-2"></span>WhatsApp</a>
        			</p>
        		</div>
        	</div>
        	<div class="col-md-6 mb-4 ftco-animate">
        		<div class="staff sponsor-card text-center">
        			<a href="https://www.amazon.com/Uma-Vida-no-Deserto-Portuguese-ebook/dp/B0DZR1D8MP/" target="_blank" rel="noopener" class="block-20 d-block" style="background-image: url(images/livro_deserto_banner.png); margin: -25px -25px 20px -25px; border-radius: 4px 4px 0 0;"></a>
        			<h3 class="mb-1"><?php echo t('Book Deserto Title'); ?></h3>
        			<p class="mb-2" style="color:#7cbd1e;font-weight:500;">Giovana Canhoni</p>
        			<p class="mb-3">
        				<span class="badge badge-pill mb-1" style="background:#fff6f2;color:#f86f2d;padding:8px 14px;"><?php echo t('Kindle Amazon Badge'); ?></span>
        			</p>
        			<p><?php echo t('Book Deserto Text'); ?></p>
        			<p class="mb-0"><a href="https://www.amazon.com/Uma-Vida-no-Deserto-Portuguese-ebook/dp/B0DZR1D8MP/" target="_blank" rel="noopener" class="btn btn-primary px-4 py-3"><span class="icon-amazon mr-2"></span><?php echo t('See On Amazon Button'); ?></a></p>
        		</div>
        	</div>
        </div>
        <?php endif; ?>
        <div class="row justify-content-center">
          <div class="col-md-8 text-center ftco-animate">
            <p><small><?php echo t('Shipping Policy Text'); ?></small></p>
          </div>
        </div>
      </div>
    </section>


<?php include 'includes/partials/footer.php'; ?>
