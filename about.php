<?php
  $pageTitle = 'About Us';
  $activePage = 'about';
  include 'includes/partials/header.php';
?>

    <div class="hero-wrap" style="background-image: url('images/bg_7.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
             <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php"><?php echo t('Home'); ?></a></span> <span><?php echo t('About'); ?></span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo t('About Us'); ?></h1>
			<h2 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }" style="color: #ffff"><?php echo t('About Hero Quote'); ?></h2>
          </div>
        </div>
      </div>
    </div>


    <section class="ftco-section">
    	<div class="container">
    		<div class="row d-flex">
    			<div class="col-md-6 d-flex ftco-animate">
    				<div class="img img-about align-self-stretch" style="background-image: url(images/bg_3.jpg); width: 100%;"></div>
    			</div>
    			<div class="col-md-6 pl-md-5 ftco-animate">
    				<h2 class="mb-4"><?php echo t('Welcome to Welfare Stablished Since 1898'); ?></h2>
					<p><?php echo t('About Intro Text'); ?></p>
					<h3><?php echo t('About Areas Heading'); ?></h3>
					<p><?php echo t('About Areas Text'); ?></p>
					<h2><?php echo t('About Mission Heading'); ?></h2>
					<p><?php echo t('About Mission Text'); ?></p>
					<h2><?php echo t('About Vision Heading'); ?></h2>
					<p><?php echo t('About Vision Text'); ?></p>
					<p><?php echo t('About Vision Prayer Text'); ?></p>
					<h2><?php echo t('About Values Heading'); ?></h2>
					<p><?php echo t('About Values Text'); ?></p>
    			</div>
    		</div>
    	</div>
    </section>

    <section class="ftco-counter ftco-intro ftco-intro-2" id="section-counter">
    	<div class="container">
    		<div class="row no-gutters">
    			<div class="col-md-5 d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18 color-1 align-items-stretch">
              <div class="text">
              	<span>Served Over</span>
                <strong class="number" data-number="1432805">0</strong>
                <span>Children in 190 countries in the world</span>
              </div>
            </div>
          </div>
          <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18 color-2 align-items-stretch">
              <div class="text">
              	<h3 class="mb-4">Donate Money</h3>
              	<p>Even the all-powerful Pointing has no control about the blind texts.</p>
              	<p><a href="#" class="btn btn-white px-3 py-2 mt-2">Donate Now</a></p>
              </div>
            </div>
          </div>
          <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18 color-3 align-items-stretch">
              <div class="text">
              	<h3 class="mb-4">Be a Volunteer</h3>
              	<p>Even the all-powerful Pointing has no control about the blind texts.</p>
              	<p><a href="#" class="btn btn-white px-3 py-2 mt-2">Be A Volunteer</a></p>
              </div>
            </div>
          </div>
    		</div>
    	</div>
    </section>

    <section class="ftco-section bg-light">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4"><?php echo t('Team Members Heading'); ?></h2>
          </div>
        </div>
        <div class="row">
        	<div class="col-12 mb-4 ftco-animate">
        		<div class="staff">
        			<div class="d-flex mb-4">
        				<div class="img" style="background-image: url(images/xandi_e_gi.jpg);"></div>
        				<div class="info ml-4">
        					<h3><a href="teacher-single.html">Alexandre e Giovana Canhoni</a></h3>
        					<div class="text">
		        				<p><?php echo t('Staff Xandi Gi Bio 1'); ?></p></br>
								<p><?php echo t('Staff Xandi Gi Bio 2'); ?></p></br>
								<p><?php echo t('Staff Xandi Gi Bio 3'); ?></p></br>
								<p><?php echo t('Staff Xandi Gi Bio 4'); ?> 
								<span><?php echo t('Bible Ref Lucas 1710'); ?></span></p>
		        			</div>
        				</div>
        			</div>
        		</div>
        	</div>
        	<div class="col-12 mb-4 ftco-animate">
        		<div class="staff">
        			<div class="d-flex mb-4">
        				<div class="img" style="background-image: url(images/glaucelir_rosa.jpg);"></div>
        				<div class="info ml-4">
        					<h3><a href="teacher-single.html">Glaucelir Rosa</a></h3>
        					<span class="position"><?php echo t('Position Missionaria'); ?></span>
        					<div class="text">
		        				<p><?php echo t('Staff Glaucelir Bio 1'); ?></p>
								<p><?php echo t('Staff Glaucelir Bio 2'); ?></p>
								<p><?php echo t('Staff Glaucelir Bio 3'); ?></p>
		        			</div>
        				</div>
        			</div>
        		</div>
        	</div>
        	<div class="col-12 mb-4 ftco-animate">
        		<div class="staff">
        			<div class="d-flex mb-4">
        				<div class="img" style="background-image: url(images/equipe_local.jpg);"></div>
        				<div class="info ml-4">
        					<h3><a href="teacher-single.html">Equipe Local</a></h3>
        					<span class="position"><?php echo t('Position Pastores Monitores'); ?></span>
        					<div class="text">
		        				<p><?php echo t('Staff Equipe Local Bio 1'); ?></p></br>
								<p><?php echo t('Staff Equipe Local Bio 2'); ?>
								<span><?php echo t('Bible Ref Joao 1223'); ?></span></p>
		        			</div>
        				</div>
        			</div>
        		</div>
        	</div>
        </div>
      </div>
    </section>

    <section class="ftco-section">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4"><?php echo t('Testimonials Heading'); ?></h2>
          </div>
        </div>
        <div class="row depoimentos">
        	<div class="col-lg-4 d-flex mb-sm-4 ftco-animate">
        		<div class="staff">
        			<div class="d-flex mb-4">
        				<div class="img-wrap">
        					<div class="img" style="background-image: url(images/Durvalina.jpg);"></div>
        					<a href="#" target="_blank" rel="noopener" class="youtube-link" aria-label="YouTube"><span class="icon-youtube-play"></span></a>
        				</div>
        				<div class="info ml-4">
        					<h3><a href="teacher-single.html">Durvalina Barreto Bezerra</a></h3>
        					<span class="position"><?php echo t('Position Teologa Betel'); ?></span>
        					<div class="text">
		        				<p><?php echo t('Testimonial Durvalina Text'); ?></p>
		        			</div>
        				</div>
        			</div>
        		</div>
        	</div>
        	<div class="col-lg-4 d-flex mb-sm-4 ftco-animate">
        		<div class="staff">
        			<div class="d-flex mb-4">
        				<div class="img-wrap">
        					<div class="img" style="background-image: url(images/Salum.jpg);"></div>
        					<a href="#" target="_blank" rel="noopener" class="youtube-link" aria-label="YouTube"><span class="icon-youtube-play"></span></a>
        				</div>
        				<div class="info ml-4">
        					<h3><a href="teacher-single.html">Josimar Salum</a></h3>
        					<span class="position"><?php echo t('Position Diretor Kings Net'); ?></span>
        					<div class="text">
		        				<p><?php echo t('Testimonial Salum Text'); ?></p>
		        			</div>
        				</div>
        			</div>
        		</div>
        	</div>
        	<div class="col-lg-4 d-flex mb-sm-4 ftco-animate">
        		<div class="staff">
        			<div class="d-flex mb-4">
        				<div class="img-wrap">
        					<div class="img" style="background-image: url(images/Misael.jpg);"></div>
        					<a href="#" target="_blank" rel="noopener" class="youtube-link" aria-label="YouTube"><span class="icon-youtube-play"></span></a>
        				</div>
        				<div class="info ml-4">
        					<h3><a href="teacher-single.html">Pr. Alírio Misael</a></h3>
        					<span class="position"><?php echo t('Position Diretor Missoes Avivamento'); ?></span>
        					<div class="text">
		        				<p><?php echo t('Testimonial Misael Text'); ?></p>
		        			</div>
        				</div>
        			</div>
        		</div>
        	</div>
        	<div class="col-lg-4 d-flex mb-sm-4 ftco-animate">
        		<div class="staff">
        			<div class="d-flex mb-4">
        				<div class="img-wrap">
        					<div class="img" style="background-image: url(images/Auderico.jpg);"></div>
        					<a href="#" target="_blank" rel="noopener" class="youtube-link" aria-label="YouTube"><span class="icon-youtube-play"></span></a>
        				</div>
        				<div class="info ml-4">
        					<h3><a href="teacher-single.html">Pr. Auderico Alves</a></h3>
        					<span class="position"><?php echo t('Position Pastor Missao Fe'); ?></span>
        					<div class="text">
		        				<p><?php echo t('Testimonial Auderico Text'); ?></p>
		        			</div>
        				</div>
        			</div>
        		</div>
        	</div>
        	<div class="col-lg-4 d-flex mb-sm-4 ftco-animate">
        		<div class="staff">
        			<div class="d-flex mb-4">
        				<div class="img-wrap">
        					<div class="img" style="background-image: url(images/Eber.jpg);"></div>
        					<a href="#" target="_blank" rel="noopener" class="youtube-link" aria-label="YouTube"><span class="icon-youtube-play"></span></a>
        				</div>
        				<div class="info ml-4">
        					<h3><a href="teacher-single.html">Pr Eber Cocareli</a></h3>
        					<span class="position"><?php echo t('Position Jornalista Teologo'); ?></span>
        					<div class="text">
		        				<p><?php echo t('Testimonial Eber Text'); ?></p>
		        			</div>
        				</div>
        			</div>
        		</div>
        	</div>
        	<div class="col-lg-4 d-flex mb-sm-4 ftco-animate">
        		<div class="staff">
        			<div class="d-flex mb-4">
        				<div class="img-wrap">
        					<div class="img" style="background-image: url(images/Ari.jpg);"></div>
        					<a href="#" target="_blank" rel="noopener" class="youtube-link" aria-label="YouTube"><span class="icon-youtube-play"></span></a>
        				</div>
        				<div class="info ml-4">
        					<h3><a href="teacher-single.html">Ari Mendes</a></h3>
        					<span class="position"><?php echo t('Position Produtor Musical'); ?></span>
        					<div class="text">
		        				<p><?php echo t('Testimonial Ari Text'); ?></p>
		        			</div>
        				</div>
        			</div>
        		</div>
        	</div>
        	<div class="col-lg-4 d-flex mb-sm-4 ftco-animate">
        		<div class="staff">
        			<div class="d-flex mb-4">
        				<div class="img-wrap">
        					<div class="img" style="background-image: url(images/roberto.jpg);"></div>
        					<a href="#" target="_blank" rel="noopener" class="youtube-link" aria-label="YouTube"><span class="icon-youtube-play"></span></a>
        				</div>
        				<div class="info ml-4">
        					<h3><a href="teacher-single.html">Roberto Ferreira do Amaral</a></h3>
        					<span class="position"><?php echo t('Position Advogado Colaborador'); ?></span>
        					<div class="text">
		        				<p><?php echo t('Testimonial Roberto Text'); ?></p>
		        			</div>
        				</div>
        			</div>
        		</div>
        	</div>
        	<div class="col-lg-4 d-flex mb-sm-4 ftco-animate">
        		<div class="staff">
        			<div class="d-flex mb-4">
        				<div class="img-wrap">
        					<div class="img" style="background-image: url(images/Mario.jpg);"></div>
        					<a href="#" target="_blank" rel="noopener" class="youtube-link" aria-label="YouTube"><span class="icon-youtube-play"></span></a>
        				</div>
        				<div class="info ml-4">
        					<h3><a href="teacher-single.html">Mario Fagundes</a></h3>
        					<span class="position"><?php echo t('Position Pastor Discipulos Salvador'); ?></span>
        					<div class="text">
		        				<p><?php echo t('Testimonial Mario Text'); ?></p>
		        			</div>
        				</div>
        			</div>
        		</div>
        	</div>
        </div>
      </div>
    </section>


<?php include 'includes/partials/footer.php'; ?>
