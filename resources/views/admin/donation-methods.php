<h1>Doações — Métodos (bancos, PIX, transferências)</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<p>
  <a href="<?php echo e(admin_url('/admin/doacoes/novo')); ?>" class="btn">+ Novo método</a>
  <a href="<?php echo e(admin_url('/admin/doacoes/paypal')); ?>" class="btn btn-secondary">Contas PayPal</a>
  <a href="<?php echo e(admin_url('/admin/doacoes/valores')); ?>" class="btn btn-secondary">Valores predefinidos</a>
</p>

<table>
  <thead><tr><th>Escopo</th><th>Tipo</th><th>Rótulo</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($methods as $m): ?>
      <tr>
        <td><?php echo $m['country_scope'] === 'national' ? 'Nacional' : 'Internacional'; ?></td>
        <td><?php echo e($m['method_type']); ?></td>
        <td><?php echo e($m['label']); ?></td>
        <td><span class="badge badge-<?php echo $m['active'] ? 'active' : 'inactive'; ?>"><?php echo $m['active'] ? 'Ativo' : 'Inativo'; ?></span></td>
        <td class="actions">
          <a class="btn btn-sm btn-secondary" href="<?php echo e(admin_url('/admin/doacoes/' . $m['id'] . '/editar')); ?>">Editar</a>
          <form method="post" action="<?php echo e(admin_url('/admin/doacoes/' . $m['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover este método?');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
