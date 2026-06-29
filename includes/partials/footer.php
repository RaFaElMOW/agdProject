<?php
require_once __DIR__ . '/../i18n.php';
require_once __DIR__ . '/../cms-bootstrap.php';

use App\Support\Settings;
?>
    <footer class="ftco-footer ftco-section img">
    	<div class="overlay"></div>
      <div class="container">
        <div class="row mb-5">
          <div class="col-md-3">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2"><?php echo t('About Us'); ?></h2>
              <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                <li class="ftco-animate"><a href="<?php echo e(Settings::get('social_facebook', 'https://www.facebook.com/agdniger')); ?>" target="_blank" rel="noopener" aria-label="Facebook"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="<?php echo e(Settings::get('social_instagram', 'https://www.instagram.com/agdniger')); ?>" target="_blank" rel="noopener" aria-label="Instagram"><span class="icon-instagram"></span></a></li>
                <li class="ftco-animate"><a href="<?php echo e(Settings::get('social_twitter', 'https://twitter.com/xandniger')); ?>" target="_blank" rel="noopener" aria-label="Twitter"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="<?php echo e(Settings::get('social_youtube', 'https://www.youtube.com/alexandrecanhoni')); ?>" target="_blank" rel="noopener" aria-label="YouTube"><span class="icon-youtube"></span></a></li>
                <li class="ftco-animate"><a href="<?php echo e(Settings::get('social_spotify', 'https://open.spotify.com/artist/2XdJcd6XJApRFv57Pj6HGf')); ?>" target="_blank" rel="noopener" aria-label="Spotify"><span class="icon-spotify"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-md-4">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2"><?php echo t('Recent Blog'); ?></h2>
              <div class="block-21 mb-4 d-flex">
                <a class="blog-img mr-4" style="background-image: url(images/image_1.jpg);"></a>
                <div class="text">
                  <h3 class="heading"><a href="#">Even the all-powerful Pointing has no control about</a></h3>
                  <div class="meta">
                    <div><a href="#"><span class="icon-calendar"></span> July 12, 2018</a></div>
                    <div><a href="#"><span class="icon-person"></span> Admin</a></div>
                    <div><a href="#"><span class="icon-chat"></span> 19</a></div>
                  </div>
                </div>
              </div>
              <div class="block-21 mb-4 d-flex">
                <a class="blog-img mr-4" style="background-image: url(images/image_2.jpg);"></a>
                <div class="text">
                  <h3 class="heading"><a href="#">Even the all-powerful Pointing has no control about</a></h3>
                  <div class="meta">
                    <div><a href="#"><span class="icon-calendar"></span> July 12, 2018</a></div>
                    <div><a href="#"><span class="icon-person"></span> Admin</a></div>
                    <div><a href="#"><span class="icon-chat"></span> 19</a></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-2">
             <div class="ftco-footer-widget mb-4 ml-md-4">
              <h2 class="ftco-heading-2"><?php echo t('Site Links'); ?></h2>
              <ul class="list-unstyled">
                <?php
                $footerLinks = \App\Support\MenuRenderer::footerLinks();
                if ($footerLinks !== ''):
                    echo $footerLinks;
                else:
                ?>
                <li><a href="#" class="py-2 d-block"><?php echo t('Home'); ?></a></li>
                <li><a href="#" class="py-2 d-block"><?php echo t('About'); ?></a></li>
                <li><a href="#" class="py-2 d-block"><?php echo t('Donate'); ?></a></li>
                <li><a href="#" class="py-2 d-block"><?php echo t('Causes'); ?></a></li>
                <li><a href="#" class="py-2 d-block"><?php echo t('Event'); ?></a></li>
                <li><a href="#" class="py-2 d-block"><?php echo t('Blog'); ?></a></li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
          <div class="col-md-3">
            <div class="ftco-footer-widget mb-4">
            	<h2 class="ftco-heading-2"><?php echo t('Have a Questions?'); ?></h2>
            	<div class="block-23 mb-3">
	              <ul>
	                <li><span class="icon icon-map-marker"></span><span class="text"><?php echo e(Settings::get('contact_address', '203 Fake St. Mountain View, San Francisco, California, USA')); ?></span></li>
	                <?php $footerPhone = Settings::get('contact_whatsapp_display', '') ?: Settings::get('contact_phone', '+2 392 3929 210'); ?>
	                <li><a href="<?php echo e(Settings::get('social_whatsapp', '#')); ?>"><span class="icon icon-phone"></span><span class="text"><?php echo e($footerPhone); ?></span></a></li>
	                <li><a href="mailto:<?php echo e(Settings::get('contact_email', 'info@yourdomain.com')); ?>"><span class="icon icon-envelope"></span><span class="text"><?php echo e(Settings::get('contact_email', 'info@yourdomain.com')); ?></span></a></li>
	              </ul>
	            </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">


            <p class="mt-3"><?php echo t('Footer AGD Copyright'); ?></p>
            <p><?php echo t('Footer Store Shipped By'); ?></p>
            <p><?php echo t('Footer Store Legal Info'); ?></p>
            <p><?php echo t('Footer Physical Product Policy'); ?></p>
            <p><?php echo t('Footer Digital Product Policy'); ?></p>
            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
          </div>
        </div>
      </div>
    </footer>


  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

  <?php echo \App\Core\View::render('partials/donation-modal'); ?>

  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function () {
        navigator.serviceWorker.register('<?php echo e(public_asset_url('sw.js')); ?>', { scope: '<?php echo e(public_asset_url('/')); ?>' })
          .catch(function () { /* PWA install is a nice-to-have, never block the page on it */ });
      });
    }
  </script>

  </body>
</html>
