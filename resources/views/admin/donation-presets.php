<h1>Doações — Valores Predefinidos</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<p><a href="<?php echo e(admin_url('/admin/doacoes')); ?>" class="btn btn-secondary">&laquo; Métodos</a></p>

<form method="post" action="<?php echo e(admin_url('/admin/doacoes/valores')); ?>" style="margin-bottom:1.5rem;">
  <?php echo csrf_field(); ?>
  <div class="grid-2">
    <div class="field"><label for="currency">Moeda</label><input type="text" id="currency" name="currency" placeholder="BRL, USD..." required></div>
    <div class="field"><label for="amount">Valor</label><input type="text" id="amount" name="amount" placeholder="50.00" required></div>
  </div>
  <button type="submit" class="btn">Adicionar</button>
</form>

<table>
  <thead><tr><th>Moeda</th><th>Valor</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($presets as $p): ?>
      <tr>
        <td><?php echo e($p['currency']); ?></td>
        <td><?php echo e($p['amount']); ?></td>
        <td>
          <form method="post" action="<?php echo e(admin_url('/admin/doacoes/valores/' . $p['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover este valor?');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
