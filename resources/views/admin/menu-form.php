<h1><?php echo $menu ? 'Editar item de menu' : 'Novo item de menu'; ?></h1>

<form method="post" action="<?php echo e($menu ? admin_url('/admin/menus/' . $menu['id']) : admin_url('/admin/menus')); ?>">
  <?php echo csrf_field(); ?>

  <div class="field">
    <label for="location">Local</label>
    <select id="location" name="location">
      <option value="header" <?php echo ($menu['location'] ?? 'header') === 'header' ? 'selected' : ''; ?>>Cabeçalho (Header)</option>
      <option value="footer" <?php echo ($menu['location'] ?? '') === 'footer' ? 'selected' : ''; ?>>Rodapé (Footer)</option>
    </select>
  </div>

  <div class="field">
    <label for="label">Rótulo</label>
    <input type="text" id="label" name="label" value="<?php echo e($menu['label'] ?? ''); ?>" required>
  </div>

  <div class="field">
    <label for="url">URL (ex: about.php, https://...)</label>
    <input type="text" id="url" name="url" value="<?php echo e($menu['url'] ?? ''); ?>" required>
  </div>

  <div class="field">
    <label for="parent_id">Item pai (para submenu/dropdown)</label>
    <select id="parent_id" name="parent_id">
      <option value="">— Nenhum (item de topo) —</option>
      <?php foreach ($parentOptions as $opt): ?>
        <option value="<?php echo (int) $opt['id']; ?>" <?php echo (int) ($menu['parent_id'] ?? 0) === (int) $opt['id'] ? 'selected' : ''; ?>><?php echo e($opt['label']); ?> (<?php echo e($opt['location']); ?>)</option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="field">
    <label for="sort_order">Ordem</label>
    <input type="text" id="sort_order" name="sort_order" value="<?php echo e((string) ($menu['sort_order'] ?? 0)); ?>">
  </div>

  <div class="field">
    <label><input type="checkbox" name="target_blank" value="1" <?php echo !empty($menu['target_blank']) ? 'checked' : ''; ?>> Abrir em nova aba</label>
  </div>

  <button type="submit">Salvar</button>
</form>
