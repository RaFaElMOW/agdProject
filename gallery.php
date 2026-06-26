<?php
  $pageTitle = 'Media';
  $activePage = 'gallery';
  include 'includes/partials/header.php';
?>

    <div class="hero-wrap" style="background-image: url('images/bg_2.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
             <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php"><?php echo t('Home'); ?></a></span> <span><?php echo t('Media'); ?></span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo t('Media'); ?></h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4"><?php echo t('Gallery Heading'); ?></h2>
            <p><?php echo t('Gallery Intro Text'); ?></p>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-gallery">
    	<div class="d-md-flex">
	    	<a href="images/midia_materia_1.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/midia_materia_1.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/midia_materia_2.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/midia_materia_2.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/midia_materia_3.png" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/midia_materia_3.png);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/midia_materia_4.png" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/midia_materia_4.png);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
    	</div>
    	<div class="d-md-flex">
	    	<a href="images/midia_materia_5.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/midia_materia_5.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/midia_materia_6.png" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/midia_materia_6.png);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/midia_pequenos_1.jpeg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/midia_pequenos_1.jpeg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    </div>
    </section>

    <section class="ftco-section pt-4 pb-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-12 text-center ftco-animate">
            <a href="blog.php" class="btn btn-primary px-4 py-3"><?php echo t('See More Button'); ?></a>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section bg-light">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4"><?php echo t('Media Heading'); ?></h2>
            <p><?php echo t('Media Intro Text'); ?></p>
          </div>
        </div>
        <div class="row d-flex">
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="https://www.youtube.com/watch?v=bqnRY6RZ8NA" class="block-20 popup-youtube" style="background-image: url(https://i.ytimg.com/vi/bqnRY6RZ8NA/hqdefault.jpg);">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-person mr-1"></span> Alexandre Canhoni</div>
                  <div><span class="icon-play mr-1"></span> <?php echo t('Video Label'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="https://www.youtube.com/watch?v=bqnRY6RZ8NA" class="popup-youtube"><?php echo t('Video Title Toda Porta'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="https://www.youtube.com/watch?v=2_4bqahBcoQ" class="block-20 popup-youtube" style="background-image: url(https://i.ytimg.com/vi/2_4bqahBcoQ/hqdefault.jpg);">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-person mr-1"></span> Alexandre Canhoni</div>
                  <div><span class="icon-play mr-1"></span> <?php echo t('Video Label'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="https://www.youtube.com/watch?v=2_4bqahBcoQ" class="popup-youtube"><?php echo t('Video Title Assista Final'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="https://www.youtube.com/watch?v=pNRswZbH1dM" class="block-20 popup-youtube" style="background-image: url(https://i.ytimg.com/vi/pNRswZbH1dM/hqdefault.jpg);">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-person mr-1"></span> Alexandre Canhoni</div>
                  <div><span class="icon-play mr-1"></span> <?php echo t('Video Label'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="https://www.youtube.com/watch?v=pNRswZbH1dM" class="popup-youtube"><?php echo t('Video Title Importancia Organizacao'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="https://www.youtube.com/watch?v=BeWINd-ZQ8I" class="block-20 popup-youtube" style="background-image: url(https://i.ytimg.com/vi/BeWINd-ZQ8I/hqdefault.jpg);">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-person mr-1"></span> Alexandre Canhoni</div>
                  <div><span class="icon-play mr-1"></span> <?php echo t('Video Label'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="https://www.youtube.com/watch?v=BeWINd-ZQ8I" class="popup-youtube"><?php echo t('Video Title Ne Renonce'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="https://www.youtube.com/watch?v=JJ_aLpGO0lE" class="block-20 popup-youtube" style="background-image: url(https://i.ytimg.com/vi/JJ_aLpGO0lE/hqdefault.jpg);">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-person mr-1"></span> Alexandre Canhoni</div>
                  <div><span class="icon-play mr-1"></span> <?php echo t('Video Label'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="https://www.youtube.com/watch?v=JJ_aLpGO0lE" class="popup-youtube"><?php echo t('Video Title Organize Agora'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="https://www.youtube.com/watch?v=UUazxatsSU8" class="block-20 popup-youtube" style="background-image: url(https://i.ytimg.com/vi/UUazxatsSU8/hqdefault.jpg);">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-person mr-1"></span> Alexandre Canhoni</div>
                  <div><span class="icon-play mr-1"></span> <?php echo t('Video Label'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="https://www.youtube.com/watch?v=UUazxatsSU8" class="popup-youtube"><?php echo t('Video Title Nova Criatura'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="https://www.youtube.com/watch?v=irj4H1vAfjY" class="block-20 popup-youtube" style="background-image: url(https://i.ytimg.com/vi/irj4H1vAfjY/hqdefault.jpg);">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-person mr-1"></span> Alexandre Canhoni</div>
                  <div><span class="icon-play mr-1"></span> <?php echo t('Video Label'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="https://www.youtube.com/watch?v=irj4H1vAfjY" class="popup-youtube"><?php echo t('Video Title Historia Niger'); ?></a></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
          	<div class="blog-entry align-self-stretch">
              <a href="https://www.youtube.com/watch?v=BADnlUyBwqM" class="block-20 popup-youtube" style="background-image: url(https://i.ytimg.com/vi/BADnlUyBwqM/hqdefault.jpg);">
              </a>
              <div class="text p-4 d-block">
              	<div class="meta mb-3">
                  <div><span class="icon-person mr-1"></span> Alexandre Canhoni</div>
                  <div><span class="icon-play mr-1"></span> <?php echo t('Video Label'); ?></div>
                </div>
                <h3 class="heading mt-3"><a href="https://www.youtube.com/watch?v=BADnlUyBwqM" class="popup-youtube"><?php echo t('Video Title Projeto Vem'); ?></a></h3>
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
    			<h3 class="mb-3"><?php echo t('Make A Donation Heading'); ?></h3>
    			<p><?php echo t('Make A Donation Text'); ?></p>
    			<p class="mb-4"><?php echo t('Pix Bank Info'); ?></p>
    			<p>
    				<a href="donate.php" class="btn btn-white py-3 px-4 mr-2 mb-2"><?php echo t('I Want To Donate Button'); ?></a>
    				<a href="https://www.agdniger.com/doacao" target="_blank" rel="noopener" class="btn btn-white btn-outline-white py-3 px-4 mb-2"><?php echo t('Donate Online Button'); ?></a>
    			</p>
    		</div>
    		</div>
    	</div>
    </section>


<?php include 'includes/partials/footer.php'; ?>
