<?php
  $pageTitle = 'Who We Are';
  $activePage = 'quemsomos';
  include 'includes/partials/header.php';
?>

    <div class="hero-wrap" style="background-image: url('images/midia_materia_1.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
             <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php"><?php echo t('Home'); ?></a></span> <span><?php echo t('Who We Are'); ?></span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo t('Who We Are'); ?></h1>
            <h2 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }" style="color: #fff">Alexandre Canhoni — CEO / Fundador / Idealizador</h2>
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
    				<h2 class="mb-4">Sobre Mim</h2>
					<p>Alexandre Canhoni é um profissional versátil e engajado, com uma carreira marcada pela dedicação às causas sociais e humanitárias.</p>
					<p>Ex-Paquito do "Xou da Xuxa", onde atuou por quatro anos na Rede Globo, Alexandre redirecionou sua trajetória para unir arte, fé e ação social em torno da transformação de vidas.</p>
					<p>É fundador da AGD Níger, organização que atua há mais de 23 anos na África, atendendo cerca de 1.000 crianças diariamente através de programas de alimentação, educação e esperança. A organização também promove alfabetização e cursos de corte e costura para mulheres, além de atividades esportivas para jovens.</p>
    			</div>
    		</div>
    	</div>
    </section>

    <section class="ftco-section bg-light">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <span class="icon-graduation-cap" style="font-size: 36px; color: #f86f2d;"></span>
            <h2 class="mb-4 mt-3">Formação Acadêmica</h2>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-8 ftco-animate">
            <ul class="list-unstyled bank-details">
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span>Bacharel em Teologia e Missiologia Transcultural (Betel Brasileiro)</li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span>Curso de Percussão e Canto Popular (ULM)</li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span>Formação em Capelania, nos EUA e no Brasil, com pós-graduação</li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span>Administração de Empresas</li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span>Certificação CPR (Cruz Vermelha Americana)</li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span>Neurolaw — Análise de Evidências Orais (Forense)</li>
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
            <h2 class="mb-4 mt-3">Atuação Internacional</h2>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-8 ftco-animate">
            <ul class="list-unstyled bank-details">
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span>Embaixador da ADMIR (Organização Humanitária Internacional)</li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span>Embaixador da Paz (UPF/ONU)</li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span>Palestrante cristão, já apresentou em mais de 80 países</li>
              <li><span class="icon-check mr-2" style="color:#f86f2d;"></span>Comendador da Ordem do Mérito do Elo Social África</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section bg-light">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4">Contato</h2>
          </div>
        </div>
        <div class="row d-flex justify-content-center contact-info text-center">
          <div class="col-md-3 mb-4">
            <p><span>WhatsApp:</span> <a href="https://api.whatsapp.com/send?phone=5511965714533" target="_blank" rel="noopener">+55 11 96571-4533</a></p>
          </div>
          <div class="col-md-3 mb-4">
            <p><span><?php echo t('Email:'); ?></span> <a href="mailto:xandcanhoni@icloud.com">xandcanhoni@icloud.com</a></p>
          </div>
          <div class="col-md-3 mb-4">
            <p><span>Instagram:</span> @alexandrecanhoni</p>
          </div>
        </div>
      </div>
    </section>


<?php include 'includes/partials/footer.php'; ?>
