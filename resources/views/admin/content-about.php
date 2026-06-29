<h1>Conteúdo — Sobre Nós</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<div class="hint">Estes campos alimentam a página "Sobre" (about.php). Equipe e depoimentos têm módulo próprio (Fase 2).</div>

<form method="post" action="<?php echo e(admin_url('/admin/conteudo/sobre')); ?>">
  <?php echo csrf_field(); ?>

  <div class="field"><label for="hero_quote">Frase de destaque (topo da página)</label><input type="text" id="hero_quote" name="hero_quote" value="<?php echo e($values['hero_quote']); ?>"></div>
  <div class="field"><label for="welcome_heading">Título de boas-vindas</label><input type="text" id="welcome_heading" name="welcome_heading" value="<?php echo e($values['welcome_heading']); ?>"></div>
  <div class="field"><label for="intro_text">Texto introdutório</label><textarea id="intro_text" name="intro_text"><?php echo e($values['intro_text']); ?></textarea></div>
  <div class="field"><label for="areas_heading">Título — Áreas de atuação</label><input type="text" id="areas_heading" name="areas_heading" value="<?php echo e($values['areas_heading']); ?>"></div>
  <div class="field"><label for="areas_text">Texto — Áreas de atuação</label><textarea id="areas_text" name="areas_text"><?php echo e($values['areas_text']); ?></textarea></div>
  <div class="field"><label for="mission_heading">Título — Missão</label><input type="text" id="mission_heading" name="mission_heading" value="<?php echo e($values['mission_heading']); ?>"></div>
  <div class="field"><label for="mission_text">Texto — Missão</label><textarea id="mission_text" name="mission_text"><?php echo e($values['mission_text']); ?></textarea></div>
  <div class="field"><label for="vision_heading">Título — Visão</label><input type="text" id="vision_heading" name="vision_heading" value="<?php echo e($values['vision_heading']); ?>"></div>
  <div class="field"><label for="vision_text">Texto — Visão</label><textarea id="vision_text" name="vision_text"><?php echo e($values['vision_text']); ?></textarea></div>
  <div class="field"><label for="vision_prayer_text">Texto — Oração/Visão (complemento)</label><textarea id="vision_prayer_text" name="vision_prayer_text"><?php echo e($values['vision_prayer_text']); ?></textarea></div>
  <div class="field"><label for="values_heading">Título — Valores</label><input type="text" id="values_heading" name="values_heading" value="<?php echo e($values['values_heading']); ?>"></div>
  <div class="field"><label for="values_text">Texto — Valores</label><textarea id="values_text" name="values_text"><?php echo e($values['values_text']); ?></textarea></div>

  <button type="submit">Salvar</button>
</form>
