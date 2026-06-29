<?php
require_once __DIR__ . '/../i18n.php';
require_once __DIR__ . '/../cms-bootstrap.php';

use App\Support\Settings;

$siteName = Settings::get('site_name', 'AGD Niger');
$metaDescription = Settings::get('seo_meta_description', '');
$ogImage = Settings::get('seo_og_image', '');
$siteLogo = Settings::get('site_logo', '');
$siteLogoWhite = Settings::get('site_logo_white', '');
$siteFavicon = Settings::get('site_favicon', '');
$gaId = Settings::get('ga_id', '');
$gtmId = Settings::get('gtm_id', '');
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars(current_lang()); ?>">
  <head>
    <title><?php echo isset($pageTitle) ? t($pageTitle) . ' - ' . htmlspecialchars($siteName) : htmlspecialchars($siteName); ?></title>
    <meta charset="utf-8">
    <base href="<?php echo e(public_asset_url('/')); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php if ($metaDescription !== ''): ?>
    <meta name="description" content="<?php echo e($metaDescription); ?>">
    <?php endif; ?>
    <?php if ($ogImage !== ''): ?>
    <meta property="og:image" content="<?php echo e(public_asset_url($ogImage)); ?>">
    <?php endif; ?>
    <?php if ($siteFavicon !== ''): ?>
    <link rel="icon" href="<?php echo e(public_asset_url($siteFavicon)); ?>">
    <?php endif; ?>

    <link rel="manifest" href="<?php echo e(public_asset_url('manifest.json')); ?>">
    <meta name="theme-color" content="#f96d00">
    <link rel="apple-touch-icon" href="<?php echo e(public_asset_url('icons/apple-touch-icon.png')); ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <link href="https://fonts.googleapis.com/css?family=Dosis:200,300,400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,400i,600,700" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">


    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <?php if ($siteLogo !== '' || $siteLogoWhite !== ''): ?>
    <style>
      <?php if ($siteLogoWhite !== ''): ?>
      /* Default (dark) navbar state — white logo, same selector/state the theme already uses. */
      .ftco-navbar-light .logo-image { background-image: url(<?php echo e(public_asset_url($siteLogoWhite)); ?>) !important; }
      <?php endif; ?>
      <?php if ($siteLogo !== ''): ?>
      /* Scrolled (light) navbar state — colored logo. */
      .ftco-navbar-light.scrolled.awake .logo-image { background-image: url(<?php echo e(public_asset_url($siteLogo)); ?>) !important; }
      <?php endif; ?>
    </style>
    <?php endif; ?>
    <?php $primaryColor = Settings::get('color_primary', ''); if ($primaryColor !== ''): ?>
    <style>
      /* Conservative override: only the well-known Bootstrap primary-button class,
         to avoid regressing colors on elements we can't fully audit in style.css. */
      .btn-primary { background-color: <?php echo e($primaryColor); ?> !important; border-color: <?php echo e($primaryColor); ?> !important; }
    </style>
    <?php endif; ?>
    <?php if ($gtmId !== ''): ?>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo e($gtmId); ?>');</script>
    <?php endif; ?>
    <?php if ($gaId !== ''): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e($gaId); ?>"></script>
    <script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', '<?php echo e($gaId); ?>');</script>
    <?php endif; ?>
  </head>
  <body>

  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="index.php"><div class="logo-image"></div></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> <?php echo t('Menu'); ?>
      </button>

      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <?php
          $dynamicNav = \App\Support\MenuRenderer::headerNav();
          if ($dynamicNav !== ''):
              echo $dynamicNav;
          else:
          ?>
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'home') ? ' active' : ''; ?>"><a href="index.php" class="nav-link"><?php echo t('Home'); ?></a></li>
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'about') ? ' active' : ''; ?>"><a href="about.php" class="nav-link"><?php echo t('About'); ?></a></li>
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'donate') ? ' active' : ''; ?>"><a href="donate.php" class="nav-link"><?php echo t('Donate'); ?></a></li>
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'gallery') ? ' active' : ''; ?>"><a href="gallery.php" class="nav-link"><?php echo t('Media'); ?></a></li>
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'sponsor') ? ' active' : ''; ?>"><a href="apadrinhar.php" class="nav-link"><?php echo t('Sponsor'); ?></a></li>
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'event') ? ' active' : ''; ?>"><a href="event.php" class="nav-link"><?php echo t('Books'); ?></a></li>
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'contact') ? ' active' : ''; ?>"><a href="contact.php" class="nav-link"><?php echo t('Contact'); ?></a></li>
          <li class="nav-item dropdown<?php echo (isset($activePage) && in_array($activePage, ['quemsomos', 'projetos'])) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="moreDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo t('More'); ?></a>
            <div class="dropdown-menu" aria-labelledby="moreDropdown">
              <a class="dropdown-item<?php echo (isset($activePage) && $activePage === 'quemsomos') ? ' active' : ''; ?>" href="quemsomos.php"><?php echo t('Who We Are'); ?></a>
              <a class="dropdown-item<?php echo (isset($activePage) && $activePage === 'projetos') ? ' active' : ''; ?>" href="projetos.php"><?php echo t('Projects'); ?></a>
            </div>
          </li>
          <?php endif; ?>
          <li class="nav-item dropdown ftco-seperator">
            <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="icon-language mr-1"></span><?php echo i18n_config()['languages'][current_lang()]['short']; ?></a>
            <div class="dropdown-menu" aria-labelledby="languageDropdown">
              <?php foreach (i18n_config()['languages'] as $langCode => $langInfo): ?>
              <a class="dropdown-item<?php echo $langCode === current_lang() ? ' active' : ''; ?>" href="set-language.php?lang=<?php echo urlencode($langCode); ?>&redirect=<?php echo urlencode(basename($_SERVER['PHP_SELF'])); ?>"><?php echo $langInfo['label']; ?></a>
              <?php endforeach; ?>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
    <!-- END nav -->
