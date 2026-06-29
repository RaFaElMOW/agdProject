<h1>Conteúdo — Quem Somos</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<div class="hint">Biografia exibida em quemsomos.php. Formação acadêmica e atuação internacional permanecem fixas nesta fase.</div>

<form method="post" action="<?php echo e(admin_url('/admin/conteudo/quemsomos')); ?>">
  <?php echo csrf_field(); ?>

  <div class="field"><label for="bio_paragraph_1">Biografia — parágrafo 1</label><textarea id="bio_paragraph_1" name="bio_paragraph_1"><?php echo e($values['bio_paragraph_1']); ?></textarea></div>
  <div class="field"><label for="bio_paragraph_2">Biografia — parágrafo 2</label><textarea id="bio_paragraph_2" name="bio_paragraph_2"><?php echo e($values['bio_paragraph_2']); ?></textarea></div>
  <div class="field"><label for="bio_paragraph_3">Biografia — parágrafo 3</label><textarea id="bio_paragraph_3" name="bio_paragraph_3"><?php echo e($values['bio_paragraph_3']); ?></textarea></div>

  <button type="submit">Salvar</button>
</form>
