<?php require_once __DIR__ . '/../i18n.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars(current_lang()); ?>">
  <head>
    <title><?php echo isset($pageTitle) ? t($pageTitle) . ' - Welfare' : 'Welfare - Free Bootstrap 4 Template by Colorlib'; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'home') ? ' active' : ''; ?>"><a href="index.php" class="nav-link"><?php echo t('Home'); ?></a></li>
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'about') ? ' active' : ''; ?>"><a href="about.php" class="nav-link"><?php echo t('About'); ?></a></li>
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'causes') ? ' active' : ''; ?>"><a href="causes.php" class="nav-link"><?php echo t('Causes'); ?></a></li>
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'donate') ? ' active' : ''; ?>"><a href="donate.php" class="nav-link"><?php echo t('Donate'); ?></a></li>
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'blog') ? ' active' : ''; ?>"><a href="blog.php" class="nav-link"><?php echo t('Blog'); ?></a></li>
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'gallery') ? ' active' : ''; ?>"><a href="gallery.php" class="nav-link"><?php echo t('Gallery'); ?></a></li>
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'event') ? ' active' : ''; ?>"><a href="event.php" class="nav-link"><?php echo t('Events'); ?></a></li>
          <li class="nav-item<?php echo (isset($activePage) && $activePage === 'contact') ? ' active' : ''; ?>"><a href="contact.php" class="nav-link"><?php echo t('Contact'); ?></a></li>
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
