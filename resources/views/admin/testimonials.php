<h1>Depoimentos</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<p><a href="<?php echo e(admin_url('/admin/depoimentos/novo')); ?>" class="btn">+ Novo depoimento</a></p>

<table>
  <thead><tr><th>Ordem</th><th>Nome</th><th>Cargo</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($testimonials as $t): ?>
      <tr>
        <td><?php echo (int) $t['sort_order']; ?></td>
        <td><?php echo e($t['name']); ?></td>
        <td><?php echo e($t['role'] ?? ''); ?></td>
        <td><span class="badge badge-<?php echo $t['active'] ? 'active' : 'inactive'; ?>"><?php echo $t['active'] ? 'Ativo' : 'Inativo'; ?></span></td>
        <td class="actions">
          <a class="btn btn-sm btn-secondary" href="<?php echo e(admin_url('/admin/depoimentos/' . $t['id'] . '/editar')); ?>">Editar</a>
          <form method="post" action="<?php echo e(admin_url('/admin/depoimentos/' . $t['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover este depoimento?');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
