<h1><?php echo $item ? 'Editar item de mídia' : 'Novo item de mídia'; ?></h1>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<form method="post" action="<?php echo e($item ? admin_url('/admin/midia/' . $item['id']) : admin_url('/admin/midia')); ?>" enctype="multipart/form-data" id="media-form">
  <?php echo csrf_field(); ?>

  <div class="field">
    <label for="type">Tipo</label>
    <select id="type" name="type" onchange="document.getElementById('img-fields').style.display = this.value === 'image' ? 'block' : 'none'; document.getElementById('video-fields').style.display = this.value === 'video' ? 'block' : 'none';">
      <option value="image" <?php echo ($item['type'] ?? 'image') === 'image' ? 'selected' : ''; ?>>Imagem</option>
      <option value="video" <?php echo ($item['type'] ?? '') === 'video' ? 'selected' : ''; ?>>Vídeo (YouTube/Vimeo/Facebook/Dailymotion/TikTok)</option>
    </select>
  </div>

  <div class="field"><label for="title">Título</label><input type="text" id="title" name="title" value="<?php echo e($item['title'] ?? ''); ?>"></div>

  <div id="img-fields" style="<?php echo ($item['type'] ?? 'image') === 'video' ? 'display:none;' : ''; ?>">
    <div class="field">
      <label for="image_file">Imagem</label>
      <?php if (!empty($item) && $item['type'] === 'image'): ?><div class="hint">Atual: <?php echo e($item['url_or_path']); ?></div><?php endif; ?>
      <input type="file" id="image_file" name="image_file" accept="image/jpeg,image/png">
    </div>
  </div>

  <div id="video-fields" style="<?php echo ($item['type'] ?? 'image') === 'video' ? '' : 'display:none;'; ?>">
    <div class="field">
      <label for="url_or_path">Link do vídeo (YouTube, Vimeo, Facebook, Dailymotion ou TikTok)</label>
      <input type="text" id="url_or_path" name="url_or_path" value="<?php echo e(($item['type'] ?? '') === 'video' ? $item['url_or_path'] : ''); ?>" placeholder="https://www.youtube.com/watch?v=...">
    </div>
    <div class="field">
      <label for="thumbnail">Miniatura (opcional — gerada automaticamente para YouTube)</label>
      <input type="text" id="thumbnail" name="thumbnail" value="<?php echo e($item['thumbnail'] ?? ''); ?>">
    </div>
  </div>

  <div class="grid-2">
    <div class="field"><label for="category">Categoria (opcional)</label><input type="text" id="category" name="category" value="<?php echo e($item['category'] ?? ''); ?>"></div>
    <div class="field"><label for="sort_order">Ordem</label><input type="text" id="sort_order" name="sort_order" value="<?php echo e((string) ($item['sort_order'] ?? 0)); ?>"></div>
  </div>

  <div class="field"><label><input type="checkbox" name="active" value="1" <?php echo ($item['active'] ?? 1) ? 'checked' : ''; ?>> Ativo (visível no site)</label></div>

  <button type="submit">Salvar</button>
</form>
