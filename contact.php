<?php
  $pageTitle = 'Contact Us';
  $activePage = 'contact';
  include 'includes/partials/header.php';
?>

    <div class="hero-wrap" style="background-image: url('images/bg_2.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
             <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php"><?php echo t('Home'); ?></a></span> <span><?php echo t('Contact'); ?></span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo t('Contact Us'); ?></h1>
          </div>
        </div>
      </div>
    </div>


    <section class="ftco-section contact-section ftco-degree-bg">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-8 heading-section ftco-animate text-center">
            <h2 class="mb-4"><?php echo t('Contact Information'); ?></h2>
            <p><?php echo t('Contact Intro Text'); ?></p>
          </div>
        </div>
        <div class="row d-flex mb-4 contact-info">
          <div class="col-md-3 mb-4">
            <p><span><?php echo t('Address:'); ?></span> AGD Níger<br>BP. 13.801<br>Niamey - Níger</p>
          </div>
          <div class="col-md-3 mb-4">
            <p><span><?php echo t('WhatsApp Label'); ?></span> <a href="https://api.whatsapp.com/send?phone=5511965714533" target="_blank" rel="noopener">+55 11 96571-4533</a></p>
          </div>
          <div class="col-md-3 mb-4">
            <p><span><?php echo t('Email:'); ?></span> <a href="mailto:alexandregiovana@uol.com.br">alexandregiovana@uol.com.br</a></p>
          </div>
          <div class="col-md-3 mb-4">
            <p><span><?php echo t('Reports Label'); ?></span> <a href="mailto:comunicacao@agdniger.com">comunicacao@agdniger.com</a></p>
          </div>
        </div>
        <div class="row mb-5">
          <div class="col-md-12 text-center">
            <ul class="ftco-social list-unstyled mb-0">
              <li class="ftco-animate"><a href="https://www.facebook.com/agdniger" target="_blank" rel="noopener" aria-label="Facebook"><span class="icon-facebook"></span></a></li>
              <li class="ftco-animate"><a href="https://www.instagram.com/agdniger" target="_blank" rel="noopener" aria-label="Instagram"><span class="icon-instagram"></span></a></li>
              <li class="ftco-animate"><a href="https://twitter.com/xandniger" target="_blank" rel="noopener" aria-label="Twitter"><span class="icon-twitter"></span></a></li>
              <li class="ftco-animate"><a href="https://www.youtube.com/alexandrecanhoni" target="_blank" rel="noopener" aria-label="YouTube"><span class="icon-youtube"></span></a></li>
              <li class="ftco-animate"><a href="https://open.spotify.com/artist/2XdJcd6XJApRFv57Pj6HGf" target="_blank" rel="noopener" aria-label="Spotify"><span class="icon-spotify"></span></a></li>
              <li class="ftco-animate"><a href="https://api.whatsapp.com/send?phone=5511965714533" target="_blank" rel="noopener" aria-label="WhatsApp"><span class="icon-whatsapp"></span></a></li>
            </ul>
          </div>
        </div>
        <div class="row block-9">
          <div class="col-md-6 pr-md-5">
          	<h4 class="mb-4"><?php echo t('Do you have any questions?'); ?></h4>
            <form action="#">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="<?php echo t('Your Name'); ?>">
              </div>
              <div class="form-group">
                <input type="text" class="form-control" placeholder="<?php echo t('Your Email'); ?>">
              </div>
              <div class="form-group">
                <select class="form-control">
                  <option value="" selected disabled><?php echo t('Select An Option'); ?></option>
                  <option value="relatorio"><?php echo t('Receive Report Option'); ?></option>
                  <option value="apadrinhamento"><?php echo t('Sponsorship Option'); ?></option>
                </select>
              </div>
              <div class="form-group">
                <textarea name="" id="" cols="30" rows="7" class="form-control" placeholder="<?php echo t('Message'); ?>"></textarea>
              </div>
              <div class="form-group">
                <input type="submit" value="<?php echo t('Send Message'); ?>" class="btn btn-primary py-3 px-5">
              </div>
            </form>

          </div>

          <div class="col-md-6" id="map"></div>
        </div>
      </div>
    </section>


<?php include 'includes/partials/footer.php'; ?>
