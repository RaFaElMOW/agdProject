<h1>Doações — Contas PayPal</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>
<div class="hint">O modal de doação usa a primeira conta ativa de cada escopo (nacional/internacional) para montar o link de redirecionamento ao PayPal.</div>

<p>
  <a href="<?php echo e(admin_url('/admin/doacoes/paypal/novo')); ?>" class="btn">+ Nova conta</a>
  <a href="<?php echo e(admin_url('/admin/doacoes')); ?>" class="btn btn-secondary">&laquo; Métodos</a>
</p>

<?php if ($accounts === []): ?>
  <div class="error">Nenhuma conta PayPal cadastrada — o botão "Doar Online" não vai funcionar até que ao menos uma conta ativa exista para o escopo nacional e/ou internacional.</div>
<?php endif; ?>

<table>
  <thead><tr><th>Escopo</th><th>Rótulo</th><th>Moeda</th><th>E-mail/ID PayPal</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($accounts as $a): ?>
      <tr>
        <td><?php echo $a['country_scope'] === 'national' ? 'Nacional' : 'Internacional'; ?></td>
        <td><?php echo e($a['label']); ?></td>
        <td><?php echo e($a['currency']); ?></td>
        <td><?php echo e($a['paypal_business_id']); ?></td>
        <td><span class="badge badge-<?php echo $a['active'] ? 'active' : 'inactive'; ?>"><?php echo $a['active'] ? 'Ativo' : 'Inativo'; ?></span></td>
        <td class="actions">
          <a class="btn btn-sm btn-secondary" href="<?php echo e(admin_url('/admin/doacoes/paypal/' . $a['id'] . '/editar')); ?>">Editar</a>
          <form method="post" action="<?php echo e(admin_url('/admin/doacoes/paypal/' . $a['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover esta conta?');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
