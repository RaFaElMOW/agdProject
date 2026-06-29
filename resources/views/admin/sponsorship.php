<h1>Apadrinhamento</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>
<div class="hint">Estes cards aparecem em apadrinhar.php e donate.php — sempre os mesmos dados, sem duplicação.</div>

<p><a href="<?php echo e(admin_url('/admin/apadrinhamento/novo')); ?>" class="btn">+ Novo card</a></p>

<table>
  <thead><tr><th>Ordem</th><th>Título</th><th>Valor</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($cards as $c): ?>
      <tr>
        <td><?php echo (int) $c['sort_order']; ?></td>
        <td><?php echo e($c['title']); ?></td>
        <td><?php echo $c['value'] !== null ? e($c['currency'] . ' ' . $c['value']) : '—'; ?></td>
        <td><span class="badge badge-<?php echo $c['status'] === 'active' ? 'active' : 'inactive'; ?>"><?php echo e($c['status']); ?></span></td>
        <td class="actions">
          <a class="btn btn-sm btn-secondary" href="<?php echo e(admin_url('/admin/apadrinhamento/' . $c['id'] . '/editar')); ?>">Editar</a>
          <form method="post" action="<?php echo e(admin_url('/admin/apadrinhamento/' . $c['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover este card?');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
