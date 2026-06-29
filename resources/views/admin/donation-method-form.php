<h1><?php echo $method ? 'Editar método de doação' : 'Novo método de doação'; ?></h1>

<form method="post" action="<?php echo e($method ? admin_url('/admin/doacoes/' . $method['id']) : admin_url('/admin/doacoes')); ?>">
  <?php echo csrf_field(); ?>

  <div class="grid-2">
    <div class="field">
      <label for="country_scope">Escopo</label>
      <select id="country_scope" name="country_scope">
        <option value="national" <?php echo ($method['country_scope'] ?? 'national') === 'national' ? 'selected' : ''; ?>>Nacional (Brasil)</option>
        <option value="international" <?php echo ($method['country_scope'] ?? '') === 'international' ? 'selected' : ''; ?>>Internacional</option>
      </select>
    </div>
    <div class="field">
      <label for="method_type">Tipo</label>
      <select id="method_type" name="method_type">
        <?php foreach (['bank' => 'Banco', 'pix' => 'PIX', 'wise' => 'Wise', 'western_union' => 'Western Union', 'zelle' => 'Zelle', 'other' => 'Outro'] as $val => $labelText): ?>
          <option value="<?php echo $val; ?>" <?php echo ($method['method_type'] ?? 'bank') === $val ? 'selected' : ''; ?>><?php echo e($labelText); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="field"><label for="label">Rótulo (ex: Banco Itaú)</label><input type="text" id="label" name="label" value="<?php echo e($method['label'] ?? ''); ?>" required></div>
  <div class="field"><label for="details">Detalhes (uma informação por linha)</label><textarea id="details" name="details" style="min-height:140px;" required><?php echo e($method['details'] ?? ''); ?></textarea></div>

  <div class="grid-2">
    <div class="field"><label for="sort_order">Ordem</label><input type="text" id="sort_order" name="sort_order" value="<?php echo e((string) ($method['sort_order'] ?? 0)); ?>"></div>
    <div class="field"><label><input type="checkbox" name="active" value="1" <?php echo ($method['active'] ?? 1) ? 'checked' : ''; ?>> Ativo</label></div>
  </div>

  <button type="submit">Salvar</button>
</form>
