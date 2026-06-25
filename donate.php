<?php
  $pageTitle = 'Donations';
  $activePage = 'donate';
  include 'includes/partials/header.php';
?>

    <div class="hero-wrap" style="background-image: url('images/bg_6.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
             <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php"><?php echo t('Home'); ?></a></span> <span><?php echo t('Donate'); ?></span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo t('Donations'); ?></h1>
            <h2 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }" style="color: #fff">Faça parte hoje! Nosso desafio é continuar mantendo mais de mil crianças nos projetos.</h2>
          </div>
        </div>
      </div>
    </div>


    <section class="ftco-section bg-light">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-8 heading-section ftco-animate text-center">
            <h2 class="mb-4">Sua Doação é Bem Vinda!</h2>
            <p>"Se o campo é o mundo, então todas as esferas precisam ser ocupadas..."<br><span>Ashbel Green Simonton</span></p>
          </div>
        </div>
        <div class="row">
        	<div class="col-lg-10 mx-auto ftco-animate">
        		<div id="accordion">
        			<div class="card">
        				<div class="card-header" id="headingBrasil">
        					<a href="#" data-toggle="collapse" data-target="#collapseBrasil" aria-expanded="true" aria-controls="collapseBrasil">
        						<span><span class="icon-bank mr-2"></span>Doações no Brasil</span>
        						<span class="icon-chevron-down"></span>
        					</a>
        				</div>
        				<div id="collapseBrasil" class="collapse show" data-parent="#accordion">
        					<div class="card-body">
        						<div class="row">
        							<div class="col-md-6 mb-4">
        								<h4>Banco Itaú</h4>
        								<ul class="list-unstyled bank-details">
        									<li><strong>Agência:</strong> 6375</li>
        									<li><strong>Conta:</strong> 01630-7</li>
        									<li><strong>Beneficiário:</strong> Instituto Social e Comunitário Guerreiros de Deus</li>
        									<li><strong>CNPJ:</strong> 08.280.906/0001-19</li>
        									<li><strong>IBAN:</strong> BR54 6070 1190 0637 5000 0016 307C 1</li>
        								</ul>
        							</div>
        							<div class="col-md-6 mb-4">
        								<h4>Banco do Brasil</h4>
        								<ul class="list-unstyled bank-details">
        									<li><strong>Agência:</strong> 3327-8</li>
        									<li><strong>Conta:</strong> 33029-9</li>
        								</ul>
        							</div>
        							<div class="col-md-6 mb-4">
        								<h4>Caixa Econômica Federal</h4>
        								<ul class="list-unstyled bank-details">
        									<li><strong>Agência:</strong> 0244</li>
        									<li><strong>Conta:</strong> 000597850629-5</li>
        									<li><strong>Titular:</strong> Walter Alexandre Canhoni</li>
        								</ul>
        							</div>
        							<div class="col-md-6 mb-4">
        								<h4>Bradesco</h4>
        								<ul class="list-unstyled bank-details">
        									<li><strong>Agência:</strong> 0764</li>
        									<li><strong>Conta:</strong> 0126319-6</li>
        									<li><strong>Titular:</strong> Walter Alexandre Canhoni</li>
        								</ul>
        							</div>
        							<div class="col-md-6">
        								<h4><span class="icon-qrcode mr-2"></span>PIX</h4>
        								<p class="bank-details">08.280.906/0001-19</p>
        							</div>
        							<div class="col-md-6">
        								<h4>Zelle</h4>
        								<p class="bank-details">mgiovanac@hotmail.com<br>alexandregiovana@uol.com.br</p>
        							</div>
        						</div>
        					</div>
        				</div>
        			</div>
        			<div class="card">
        				<div class="card-header" id="headingInternacional">
        					<a href="#" class="collapsed" data-toggle="collapse" data-target="#collapseInternacional" aria-expanded="false" aria-controls="collapseInternacional">
        						<span><span class="icon-globe mr-2"></span>Doações Internacionais</span>
        						<span class="icon-chevron-down"></span>
        					</a>
        				</div>
        				<div id="collapseInternacional" class="collapse" data-parent="#accordion">
        					<div class="card-body">
        						<div class="row">
        							<div class="col-md-6 mb-4">
        								<h4>Ecobank Niger (Niamey)</h4>
        								<ul class="list-unstyled bank-details">
        									<li><strong>SWIFT:</strong> ECOCNENIXXX</li>
        									<li><strong>IBAN:</strong> NE09 5010 0616 0050 0030 0167</li>
        									<li><strong>Conta:</strong> Association Guerreiros de Deus</li>
        								</ul>
        							</div>
        							<div class="col-md-6 mb-4">
        								<h4>Western Union</h4>
        								<p class="bank-details">Envie um e-mail para receber os detalhes de envio.</p>
        							</div>
        							<div class="col-md-6">
        								<h4>Wise</h4>
        								<p class="bank-details">@walter58c</p>
        							</div>
        						</div>
        					</div>
        				</div>
        			</div>
        			<div class="card">
        				<div class="card-header" id="headingOnline">
        					<a href="#" class="collapsed" data-toggle="collapse" data-target="#collapseOnline" aria-expanded="false" aria-controls="collapseOnline">
        						<span><span class="icon-credit-card mr-2"></span>Pagamento Online</span>
        						<span class="icon-chevron-down"></span>
        					</a>
        				</div>
        				<div id="collapseOnline" class="collapse" data-parent="#accordion">
        					<div class="card-body">
        						<p>Você também pode doar online via <strong>PayPal</strong> (em Reais, Dólares ou Euros), <strong>PagSeguro</strong> ou <strong>Boleto</strong> (enviado por e-mail).</p>
        						<p class="mb-0"><a href="https://www.agdniger.com/doacao" target="_blank" rel="noopener" class="btn btn-primary px-4 py-3"><span class="icon-paypal mr-2"></span>Doar Online</a></p>
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
            <h2 class="mb-4">Campanha de Alimento</h2>
            <p>Ajude a garantir alimento para as famílias e crianças atendidas pelos projetos da AGD no Níger.</p>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-8 text-center ftco-animate">
            <h3 class="mb-4">Como Ajudar?</h3>
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
              	<span class="icon-money d-block mb-2" style="font-size: 30px;"></span>
              	<h3 class="mb-3">Doação de Valores</h3>
              	<p>Contribua financeiramente para a compra de alimentos para as famílias atendidas.</p>
              </div>
            </div>
          </div>
          <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18 color-2 align-items-stretch">
              <div class="text">
              	<span class="icon-handshake-o d-block mb-2" style="font-size: 30px;"></span>
              	<h3 class="mb-3">Conhecimento e Tempo</h3>
              	<p>Compartilhe seu tempo, conhecimento ou habilidades com os projetos da AGD.</p>
              </div>
            </div>
          </div>
          <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18 color-3 align-items-stretch">
              <div class="text">
              	<span class="icon-shopping-basket d-block mb-2" style="font-size: 30px;"></span>
              	<h3 class="mb-3">Materiais</h3>
              	<p>Doe alimentos, roupas ou outros materiais que ajudem diretamente as famílias.</p>
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
            <h2 class="mb-4">Apadrinhe</h2>
            <p>Faça parte da vida de uma criança ou de uma creche no Níger através do apadrinhamento.</p>
          </div>
        </div>
        <div class="row">
        	<div class="col-md-6 mb-4 ftco-animate">
        		<div class="staff sponsor-card text-center">
        			<div class="icon-wrap"><span class="icon-child"></span></div>
        			<h3 class="mb-2">Apadrinhe uma Criança</h3>
        			<p class="sponsor-price">USD 30 <span>/mês</span></p>
        			<p>Apadrinhamento individual, com envio de foto e atualizações da criança. No Brasil, também é possível apadrinhar de forma compartilhada por R$ 60,00 mensais.</p>
        			<p class="mb-0"><a href="mailto:comunicacao@agdniger.com?subject=Quero%20apadrinhar%20uma%20crian%C3%A7a" class="btn btn-primary px-4 py-3">Quero Apadrinhar</a></p>
        		</div>
        	</div>
        	<div class="col-md-6 mb-4 ftco-animate">
        		<div class="staff sponsor-card text-center">
        			<div class="icon-wrap"><span class="icon-home2"></span></div>
        			<h3 class="mb-2">Apadrinhe uma Creche</h3>
        			<p class="sponsor-price">R$ 200 <span>ou USD 100 /mês</span></p>
        			<p>Apoie o professor de uma creche com 40 crianças, ajudando a manter a estrutura e o ensino em funcionamento.</p>
        			<p class="mb-0"><a href="mailto:comunicacao@agdniger.com?subject=Quero%20apadrinhar%20uma%20creche" class="btn btn-primary px-4 py-3">Quero Apadrinhar</a></p>
        		</div>
        	</div>
        </div>
      </div>
    </section>


<?php include 'includes/partials/footer.php'; ?>
