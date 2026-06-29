<?php
  $pageTitle = 'Projects';
  $activePage = 'projetos';

  require_once __DIR__ . '/includes/cms-bootstrap.php';
  $dynamicProjects = (new \App\Repositories\ProjectRepository())->activeOrdered();

  include 'includes/partials/header.php';
?>

    <div class="hero-wrap" style="background-image: url('images/bg_5.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
             <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php"><?php echo t('Home'); ?></a></span> <span><?php echo t('Projects'); ?></span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo t('Projects'); ?></h1>
          </div>
        </div>
      </div>
    </div>


    <section class="ftco-section">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-8 heading-section ftco-animate text-center">
            <h2 class="mb-4"><?php echo t('What We Do Heading'); ?></h2>
            <p><?php echo t('What We Do Text'); ?></p>
          </div>
        </div>
        <?php if ($dynamicProjects !== []): ?>
        <div class="row">
          <?php foreach ($dynamicProjects as $proj): ?>
          <div class="col-md-4 ftco-animate">
            <div class="cause-entry">
              <a href="<?php echo e('projeto.php?slug=' . $proj['slug']); ?>" class="img" style="background-image: url(<?php echo e(public_asset_url($proj['banner'] ?: 'images/cause-1.jpg')); ?>);"></a>
              <div class="text p-3 p-md-4">
                <h3><a href="<?php echo e('projeto.php?slug=' . $proj['slug']); ?>"><?php echo e($proj['name']); ?></a></h3>
                <p><?php echo e(mb_strimwidth((string) $proj['description'], 0, 160, '...')); ?></p>
                <p class="mb-0"><a href="<?php echo e('projeto.php?slug=' . $proj['slug']); ?>"><?php echo t('Learn More Link'); ?> <i class="ion-ios-arrow-forward"></i></a></p>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
      	<div class="row">
      		<div class="col-md-4 ftco-animate">
      			<div class="cause-entry">
    					<a href="https://www.agdniger.com/nutricao" target="_blank" rel="noopener" class="img" style="background-image: url(images/cause-1.jpg);"></a>
    					<div class="text p-3 p-md-4">
    						<h3><a href="https://www.agdniger.com/nutricao" target="_blank" rel="noopener"><?php echo t('Project Nutricao Title'); ?></a></h3>
    						<p><?php echo t('Project Nutricao Text'); ?></p>
    						<p class="mb-0"><a href="https://www.agdniger.com/nutricao" target="_blank" rel="noopener"><?php echo t('Learn More Link'); ?> <i class="ion-ios-arrow-forward"></i></a></p>
    					</div>
    				</div>
      		</div>
      		<div class="col-md-4 ftco-animate">
      			<div class="cause-entry">
    					<a href="https://www.agdniger.com/mulheres" target="_blank" rel="noopener" class="img" style="background-image: url(images/image_5.jpg);"></a>
    					<div class="text p-3 p-md-4">
    						<h3><a href="https://www.agdniger.com/mulheres" target="_blank" rel="noopener"><?php echo t('Project Mulheres Title'); ?></a></h3>
    						<p><?php echo t('Project Mulheres Text'); ?></p>
    						<p class="mb-0"><a href="https://www.agdniger.com/mulheres" target="_blank" rel="noopener"><?php echo t('Learn More Link'); ?> <i class="ion-ios-arrow-forward"></i></a></p>
    					</div>
    				</div>
      		</div>
      		<div class="col-md-4 ftco-animate">
      			<div class="cause-entry">
    					<a href="https://youtu.be/NsYu3ZtkrJw" target="_blank" rel="noopener" class="img" style="background-image: url(images/cause-3.jpg);"></a>
    					<div class="text p-3 p-md-4">
    						<h3><a href="https://youtu.be/NsYu3ZtkrJw" target="_blank" rel="noopener"><?php echo t('Project Reabilitacao Title'); ?></a></h3>
    						<p><?php echo t('Project Reabilitacao Text'); ?></p>
    						<p class="mb-0"><a href="https://youtu.be/NsYu3ZtkrJw" target="_blank" rel="noopener"><span class="icon-play mr-1"></span><?php echo t('Watch Link'); ?></a></p>
    					</div>
    				</div>
      		</div>
      		<div class="col-md-4 ftco-animate">
      			<div class="cause-entry">
    					<a href="apadrinhar.php" class="img" style="background-image: url(images/image_1.jpg);"></a>
    					<div class="text p-3 p-md-4">
    						<h3><a href="apadrinhar.php"><?php echo t('Project Creches Title'); ?></a></h3>
    						<p><?php echo t('Project Creches Text'); ?></p>
    						<p class="mb-0"><a href="apadrinhar.php"><?php echo t('Learn More Link'); ?> <i class="ion-ios-arrow-forward"></i></a></p>
    					</div>
    				</div>
      		</div>
      		<div class="col-md-4 ftco-animate">
      			<div class="cause-entry">
    					<a href="https://www.agdniger.com/esportes" target="_blank" rel="noopener" class="img" style="background-image: url(images/event-3.jpg);"></a>
    					<div class="text p-3 p-md-4">
    						<h3><a href="https://www.agdniger.com/esportes" target="_blank" rel="noopener"><?php echo t('Project Esporte Title'); ?></a></h3>
    						<p><?php echo t('Project Esporte Text'); ?></p>
    						<p class="mb-0"><a href="https://www.agdniger.com/esportes" target="_blank" rel="noopener"><?php echo t('Learn More Link'); ?> <i class="ion-ios-arrow-forward"></i></a></p>
    					</div>
    				</div>
      		</div>
      		<div class="col-md-4 ftco-animate">
      			<div class="cause-entry">
    					<div class="img" style="background-image: url(images/event-5.jpg);"></div>
    					<div class="text p-3 p-md-4">
    						<h3><?php echo t('Project Deficientes Title'); ?></h3>
    						<p><?php echo t('Project Deficientes Text'); ?></p>
    					</div>
    				</div>
      		</div>
      		<div class="col-md-4 ftco-animate">
      			<div class="cause-entry">
    					<div class="img" style="background-image: url(images/cause-6.jpg);"></div>
    					<div class="text p-3 p-md-4">
    						<h3><?php echo t('Project Escola Title'); ?></h3>
    						<p><?php echo t('Project Escola Text'); ?></p>
    					</div>
    				</div>
      		</div>
      		<div class="col-md-4 ftco-animate">
      			<div class="cause-entry">
    					<a href="https://www.agdniger.com/hospital-e-escola-cegos" target="_blank" rel="noopener" class="img" style="background-image: url(images/image_6.jpg);"></a>
    					<div class="text p-3 p-md-4">
    						<h3><a href="https://www.agdniger.com/hospital-e-escola-cegos" target="_blank" rel="noopener"><?php echo t('Project Hospital Title'); ?></a></h3>
    						<p><?php echo t('Project Hospital Text'); ?></p>
    						<p class="mb-0"><a href="https://www.agdniger.com/hospital-e-escola-cegos" target="_blank" rel="noopener"><?php echo t('Learn More Link'); ?> <i class="ion-ios-arrow-forward"></i></a></p>
    					</div>
    				</div>
      		</div>
      		<div class="col-md-4 ftco-animate">
      			<div class="cause-entry">
    					<div class="img" style="background-image: url(images/equipe_local.jpg);"></div>
    					<div class="text p-3 p-md-4">
    						<h3><?php echo t('Project Obreiros Title'); ?></h3>
    						<p><?php echo t('Project Obreiros Text'); ?></p>
    					</div>
    				</div>
      		</div>
        </div>
        <?php endif; ?>
      </div>
    </section>

    <section class="ftco-section bg-light">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4"><?php echo t('Where We Work Heading'); ?></h2>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-counter ftco-intro ftco-intro-2">
    	<div class="container">
    		<div class="row no-gutters">
          <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18 color-1 align-items-stretch">
              <div class="text">
              	<h3 class="mb-3"><?php echo t('Brazil Region Heading'); ?></h3>
              	<p><?php echo t('Brazil Region Text 1'); ?></p>
              	<p><?php echo t('Brazil Region Text 2'); ?></p>
              </div>
            </div>
          </div>
          <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18 color-2 align-items-stretch">
              <div class="text">
              	<h3 class="mb-3"><?php echo t('India Region Heading'); ?></h3>
              	<p><?php echo t('India Region Text 1'); ?></p>
              	<p><?php echo t('India Region Text 2'); ?></p>
              	<p><em><?php echo t('India Project Paused Text'); ?></em></p>
              </div>
            </div>
          </div>
          <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18 color-3 align-items-stretch">
              <div class="text">
              	<h3 class="mb-3"><?php echo t('Niger Region Heading'); ?></h3>
              	<p><?php echo t('Niger Region Text 1'); ?></p>
              	<p><?php echo t('Niger Region Text 2'); ?></p>
              </div>
            </div>
          </div>
    		</div>
    	</div>
    </section>

    <section class="ftco-section-3 img" style="background-image: url(images/bg_3.jpg);">
    	<div class="overlay"></div>
    	<div class="container">
    		<div class="row d-md-flex">
    		<div class="col-md-6 d-flex ftco-animate">
    			<div class="img img-2 align-self-stretch" style="background-image: url(images/bg_4.jpg);"></div>
    		</div>
    		<div class="col-md-6 volunteer pl-md-5 ftco-animate">
    			<?php echo \App\Core\View::render('partials/donation-cta'); ?>
    		</div>
    		</div>
    	</div>
    </section>


<?php include 'includes/partials/footer.php'; ?>
