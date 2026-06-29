<?php
  $pageTitle = 'Sponsor';
  $activePage = 'sponsor';

  require_once __DIR__ . '/includes/cms-bootstrap.php';
  $sponsorshipCards = (new \App\Repositories\SponsorshipCardRepository())->activeOrdered();

  include 'includes/partials/header.php';
?>

    <div class="hero-wrap" style="background-image: url('images/image_4.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
             <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php"><?php echo t('Home'); ?></a></span> <span><?php echo t('Sponsor'); ?></span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo t('Sponsor Child Hero Title'); ?></h1>
            <h2 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }" style="color: #fff"><?php echo t('Sponsor Hero Subtitle'); ?></h2>
          </div>
        </div>
      </div>
    </div>


    <section class="ftco-section bg-light">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-8 heading-section ftco-animate text-center">
            <h2 class="mb-4"><?php echo t('School Sponsorship Heading'); ?></h2>
            <p><?php echo t('School Sponsorship Text 1'); ?></p>
            <p><?php echo t('School Sponsorship Text 2'); ?></p>
            <p><?php echo t('School Sponsorship Text 3'); ?></p>
          </div>
        </div>
        <?php if ($sponsorshipCards !== []): ?>
        <div class="row justify-content-center">
          <?php foreach ($sponsorshipCards as $card): ?>
            <?php echo \App\Core\View::render('partials/sponsorship-card', ['card' => $card]); ?>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="row justify-content-center">
          <div class="col-md-6 ftco-animate">
        		<div class="staff sponsor-card text-center">
        			<div class="icon-wrap"><span class="icon-child"></span></div>
        			<h3 class="mb-2"><?php echo t('Sponsor Child School Daycare Heading'); ?></h3>
        			<p class="sponsor-price">USD 30 <span><?php echo t('Per Month Label'); ?></span></p>
        			<p><?php echo t('Sponsor Investment Text'); ?></p>
        			<p><?php echo t('Shared Sponsorship Text'); ?></p>
        			<p class="mb-3">
        				<span class="badge badge-pill mr-1 mb-1" style="background:#fff6f2;color:#f86f2d;padding:8px 14px;"><span class="icon-paypal mr-1"></span><?php echo t('Paypal Credit Card Badge'); ?></span>
        				<span class="badge badge-pill mr-1 mb-1" style="background:#fff6f2;color:#f86f2d;padding:8px 14px;"><?php echo t('Euro Badge'); ?></span>
        				<span class="badge badge-pill mr-1 mb-1" style="background:#fff6f2;color:#f86f2d;padding:8px 14px;"><?php echo t('Reais Badge'); ?></span>
        				<span class="badge badge-pill mb-1" style="background:#fff6f2;color:#f86f2d;padding:8px 14px;"><?php echo t('Dolar Badge'); ?></span>
        			</p>
        			<p class="mb-0">
        				<a href="mailto:comunicacao@agdniger.com?subject=Quero%20apadrinhar%20uma%20crian%C3%A7a" class="btn btn-primary px-4 py-3 mr-2 mb-2"><?php echo t('I Want To Sponsor Button'); ?></a>
        				<a href="https://www.agdniger.com/doacao" target="_blank" rel="noopener" class="btn btn-outline-primary px-4 py-3 mb-2"><?php echo t('Donate Online Button'); ?></a>
        			</p>
        		</div>
          </div>
        </div>
        <?php endif; ?>
        <div class="row justify-content-center mt-5">
          <div class="col-md-8 text-center ftco-animate">
            <p><?php echo t('Inspiring Closing Text'); ?></p>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4"><?php echo t('Whats Included Heading'); ?></h2>
            <p><?php echo t('Whats Included Text'); ?></p>
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
              	<span class="icon-cutlery d-block mb-2" style="font-size: 30px;"></span>
              	<h3 class="mb-3"><?php echo t('Breakfast Heading'); ?></h3>
              	<p><?php echo t('Breakfast Text'); ?></p>
              </div>
            </div>
          </div>
          <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18 color-2 align-items-stretch">
              <div class="text">
              	<span class="icon-bus d-block mb-2" style="font-size: 30px;"></span>
              	<h3 class="mb-3"><?php echo t('Transport Heading'); ?></h3>
              	<p><?php echo t('Transport Text'); ?></p>
              </div>
            </div>
          </div>
          <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18 color-3 align-items-stretch">
              <div class="text">
              	<span class="icon-restaurant d-block mb-2" style="font-size: 30px;"></span>
              	<h3 class="mb-3"><?php echo t('Lunch Heading'); ?></h3>
              	<p><?php echo t('Lunch Text'); ?></p>
              </div>
            </div>
          </div>
    		</div>
    	</div>
    </section>

    <section class="ftco-section">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-8 heading-section ftco-animate text-center">
            <h2 class="mb-4"><?php echo t('Know Niger Heading'); ?></h2>
            <p><?php echo t('Know Niger Text'); ?></p>
          </div>
        </div>
        <div class="row">
        	<div class="col-md-6 mb-4 ftco-animate">
        		<a href="https://www.youtube.com/watch?v=Ng7Nu4_8b1g" class="gallery popup-youtube video-thumb d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(https://img.youtube.com/vi/Ng7Nu4_8b1g/hqdefault.jpg); width: 100%; height: 250px;">
        			<div class="icon d-flex justify-content-center align-items-center">
        				<span class="icon-play"></span>
        			</div>
        		</a>
        		<h3 class="mt-3 mb-0" style="font-size: 18px;"><?php echo t('Video Title What Is Being Done'); ?></h3>
        	</div>
        	<div class="col-md-6 mb-4 ftco-animate">
        		<a href="https://www.youtube.com/watch?v=f6wy66LS-Yc" class="gallery popup-youtube video-thumb d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(https://img.youtube.com/vi/f6wy66LS-Yc/hqdefault.jpg); width: 100%; height: 250px;">
        			<div class="icon d-flex justify-content-center align-items-center">
        				<span class="icon-play"></span>
        			</div>
        		</a>
        		<h3 class="mt-3 mb-0" style="font-size: 18px;"><?php echo t('Video Title Living Conditions'); ?></h3>
        	</div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-8 text-center ftco-animate">
            <p><?php echo t('Videos Closing Text'); ?></p>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section bg-light">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4"><?php echo t('Daycare Heading'); ?></h2>
            <p><?php echo t('Daycare Text'); ?></p>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-gallery">
    	<div class="d-md-flex">
	    	<a href="images/image_1.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/image_1.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/image_2.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/image_2.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/image_3.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/image_3.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/image_5.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/image_5.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
    	</div>
    	<div class="d-md-flex">
	    	<a href="images/image_6.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/image_6.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/cause-3.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/cause-3.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
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
    			<h3 class="mb-3"><?php echo t('Still Have Questions Heading'); ?></h3>
    			<p><?php echo t('Still Have Questions Text'); ?></p>
    			<p>
    				<a href="https://wa.me/5511965714533" target="_blank" rel="noopener" class="btn btn-white px-4 py-3 mr-2 mb-2"><span class="icon-whatsapp mr-2"></span>WhatsApp</a>
    				<a href="mailto:comunicacao@agdniger.com" class="btn btn-white btn-outline-white px-4 py-3 mb-2"><span class="icon-envelope mr-2"></span><?php echo t('Email Button'); ?></a>
    			</p>
    		</div>
    		</div>
    	</div>
    </section>


<?php include 'includes/partials/footer.php'; ?>
