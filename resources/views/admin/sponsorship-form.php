<h1><?php echo $card ? 'Editar card de apadrinhamento' : 'Novo card de apadrinhamento'; ?></h1>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<form method="post" action="<?php echo e($card ? admin_url('/admin/apadrinhamento/' . $card['id']) : admin_url('/admin/apadrinhamento')); ?>" enctype="multipart/form-data">
  <?php echo csrf_field(); ?>

  <div class="field"><label for="title">Título</label><input type="text" id="title" name="title" value="<?php echo e($card['title'] ?? ''); ?>" required></div>
  <div class="field"><label for="description">Descrição</label><textarea id="description" name="description"><?php echo e($card['description'] ?? ''); ?></textarea></div>

  <div class="grid-2">
    <div class="field"><label for="value">Valor</label><input type="text" id="value" name="value" value="<?php echo e((string) ($card['value'] ?? '')); ?>" placeholder="30.00"></div>
    <div class="field"><label for="currency">Moeda</label><input type="text" id="currency" name="currency" value="<?php echo e($card['currency'] ?? 'USD'); ?>" placeholder="USD, BRL..."></div>
  </div>

  <div class="grid-2">
    <div class="field"><label for="icon">Ícone (classe do tema, ex: icon-child, icon-home2)</label><input type="text" id="icon" name="icon" value="<?php echo e($card['icon'] ?? ''); ?>"></div>
    <div class="field"><label for="cta_link">Link do botão (mailto: ou URL)</label><input type="text" id="cta_link" name="cta_link" value="<?php echo e($card['cta_link'] ?? ''); ?>" placeholder="mailto:comunicacao@agdniger.com"></div>
  </div>

  <div class="field">
    <label for="image">Imagem (opcional)</label>
    <?php if (!empty($card['image'])): ?><div class="hint">Atual: <?php echo e($card['image']); ?></div><?php endif; ?>
    <input type="file" id="image" name="image" accept="image/jpeg,image/png">
  </div>

  <div class="grid-2">
    <div class="field"><label for="sort_order">Ordem</label><input type="text" id="sort_order" name="sort_order" value="<?php echo e((string) ($card['sort_order'] ?? 0)); ?>"></div>
    <div class="field">
      <label for="status">Status</label>
      <select id="status" name="status">
        <option value="active" <?php echo ($card['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Ativo</option>
        <option value="inactive" <?php echo ($card['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inativo</option>
      </select>
    </div>
  </div>

  <button type="submit">Salvar</button>
</form>
