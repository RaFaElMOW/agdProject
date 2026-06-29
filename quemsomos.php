<?php
  $pageTitle = 'Who We Are';
  $activePage = 'quemsomos';

  require_once __DIR__ . '/includes/cms-bootstrap.php';
  $quemSomosContent = (new \App\Repositories\SiteContentRepository())->get('quemsomos') ?? [];
  $qs = function (string $field, string $fallbackKey) use ($quemSomosContent) {
      return !empty($quemSomosContent[$field]) ? e($quemSomosContent[$field]) : t($fallbackKey);
  };

  include 'includes/partials/header.php';
?>

    <div class="hero-wrap" style="background-image: url('images/midia_materia_1.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
             <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php"><?php echo t('Home'); ?></a></span> <span><?php echo t('Who We Are'); ?></span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo t('Who We Are'); ?></h1>
            <h2 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }" style="color: #fff"><?php echo t('Alexandre Title Role'); ?></h2>
          </div>
        </div>
      </div>
    </div>


    <section class="ftco-section">
    	<div class="container">
    		<div class="row d-flex">
    			<div class="col-md-5 d-flex ftco-animate">
    				<div class="img img-about align-self-stretch" style="background-image: url(images/midia_materia_1.jpg); width: 100%;"></div>
    			</div>
    			<div class="col-md-7 pl-md-5 ftco-animate">
    				<h2 class="mb-4"><?php echo t('About Me Heading'); ?></h2>
					<p><?php echo $qs('bio_paragraph_1', 'Alexandre Bio Text 1'); ?></p>
					<p><?php echo $qs('bio_paragraph_2', 'Alexandre Bio Text 2'); ?></p>
					<p><?php echo $qs('bio_paragraph_3', 'Alexandre Bio Text 3'); ?></p>
    			</div>
    		</div>
    	</div>
    </section>

    <section class="ftco-section bg-light">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <span class="icon-graduation-cap" style="font-size: 36px; color: #f86f2d;"></span>
            <h2 class="mb-4 mt-3"><?php echo t('Academic Formation Heading'); ?></h2>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-8 ftco-animate">
            <ul class="list-unstyled bank-details">
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span><?php echo t('Degree Theology'); ?></li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span><?php echo t('Degree Percussion'); ?></li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span><?php echo t('Degree Chaplaincy'); ?></li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span><?php echo t('Degree Business Admin'); ?></li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span><?php echo t('Degree CPR'); ?></li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span><?php echo t('Degree Neurolaw'); ?></li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <span class="icon-trophy" style="font-size: 36px; color: #f86f2d;"></span>
            <h2 class="mb-4 mt-3"><?php echo t('International Role Heading'); ?></h2>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-8 ftco-animate">
            <ul class="list-unstyled bank-details">
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span><?php echo t('Role Admir'); ?></li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span><?php echo t('Role Peace Ambassador'); ?></li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span><?php echo t('Role Speaker'); ?></li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span><?php echo t('Role Comendador'); ?></li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section bg-light">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4"><?php echo t('Contact Heading Short'); ?></h2>
          </div>
        </div>
        <div class="row d-flex justify-content-center contact-info text-center">
          <div class="col-md-3 mb-4">
            <p><span><?php echo t('WhatsApp Label'); ?></span> <a href="https://api.whatsapp.com/send?phone=5511965714533" target="_blank" rel="noopener">+55 11 96571-4533</a></p>
          </div>
          <div class="col-md-3 mb-4">
            <p><span><?php echo t('Email:'); ?></span> <a href="mailto:xandcanhoni@icloud.com">xandcanhoni@icloud.com</a></p>
          </div>
          <div class="col-md-3 mb-4">
            <p><span><?php echo t('Instagram Label'); ?></span> @alexandrecanhoni</p>
          </div>
        </div>
      </div>
    </section>


<?php include 'includes/partials/footer.php'; ?>
