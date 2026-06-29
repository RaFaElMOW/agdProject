<h1><?php echo $account ? 'Editar conta PayPal' : 'Nova conta PayPal'; ?></h1>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<form method="post" action="<?php echo e($account ? admin_url('/admin/doacoes/paypal/' . $account['id']) : admin_url('/admin/doacoes/paypal')); ?>">
  <?php echo csrf_field(); ?>

  <div class="field"><label for="label">Rótulo (uso interno)</label><input type="text" id="label" name="label" value="<?php echo e($account['label'] ?? ''); ?>" required></div>
  <div class="field"><label for="paypal_business_id">E-mail ou ID comercial PayPal</label><input type="text" id="paypal_business_id" name="paypal_business_id" value="<?php echo e($account['paypal_business_id'] ?? ''); ?>" required></div>

  <div class="grid-2">
    <div class="field">
      <label for="country_scope">Escopo</label>
      <select id="country_scope" name="country_scope">
        <option value="national" <?php echo ($account['country_scope'] ?? '') === 'national' ? 'selected' : ''; ?>>Nacional (Brasil)</option>
        <option value="international" <?php echo ($account['country_scope'] ?? 'international') === 'international' ? 'selected' : ''; ?>>Internacional</option>
      </select>
    </div>
    <div class="field"><label for="currency">Moeda</label><input type="text" id="currency" name="currency" value="<?php echo e($account['currency'] ?? 'USD'); ?>"></div>
  </div>

  <div class="grid-2">
    <div class="field"><label for="sort_order">Ordem</label><input type="text" id="sort_order" name="sort_order" value="<?php echo e((string) ($account['sort_order'] ?? 0)); ?>"></div>
    <div class="field"><label><input type="checkbox" name="active" value="1" <?php echo ($account['active'] ?? 1) ? 'checked' : ''; ?>> Ativo</label></div>
  </div>

  <button type="submit">Salvar</button>
</form>
