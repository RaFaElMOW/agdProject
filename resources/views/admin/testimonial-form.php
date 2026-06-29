<h1><?php echo $testimonial ? 'Editar depoimento' : 'Novo depoimento'; ?></h1>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<form method="post" action="<?php echo e($testimonial ? admin_url('/admin/depoimentos/' . $testimonial['id']) : admin_url('/admin/depoimentos')); ?>" enctype="multipart/form-data">
  <?php echo csrf_field(); ?>

  <div class="grid-2">
    <div class="field"><label for="name">Nome</label><input type="text" id="name" name="name" value="<?php echo e($testimonial['name'] ?? ''); ?>" required></div>
    <div class="field"><label for="role">Cargo / Posição</label><input type="text" id="role" name="role" value="<?php echo e($testimonial['role'] ?? ''); ?>"></div>
  </div>

  <div class="field">
    <label for="photo">Foto</label>
    <?php if (!empty($testimonial['photo'])): ?><div class="hint">Atual: <?php echo e($testimonial['photo']); ?></div><?php endif; ?>
    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png">
  </div>

  <div class="field"><label for="text">Depoimento</label><textarea id="text" name="text" required><?php echo e($testimonial['text'] ?? ''); ?></textarea></div>
  <div class="field"><label for="youtube_url">Link do vídeo (YouTube, opcional)</label><input type="text" id="youtube_url" name="youtube_url" value="<?php echo e($testimonial['youtube_url'] ?? ''); ?>"></div>

  <div class="grid-2">
    <div class="field"><label for="sort_order">Ordem</label><input type="text" id="sort_order" name="sort_order" value="<?php echo e((string) ($testimonial['sort_order'] ?? 0)); ?>"></div>
    <div class="field"><label><input type="checkbox" name="active" value="1" <?php echo ($testimonial['active'] ?? 1) ? 'checked' : ''; ?>> Ativo (visível no site)</label></div>
  </div>

  <button type="submit">Salvar</button>
</form>
