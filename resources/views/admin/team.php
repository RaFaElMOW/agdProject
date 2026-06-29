<h1>Equipe</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<p><a href="<?php echo e(admin_url('/admin/equipe/novo')); ?>" class="btn">+ Novo membro</a></p>

<table>
  <thead><tr><th>Ordem</th><th>Nome</th><th>Cargo</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($members as $m): ?>
      <tr>
        <td><?php echo (int) $m['sort_order']; ?></td>
        <td><?php echo e($m['name']); ?></td>
        <td><?php echo e($m['role'] ?? ''); ?></td>
        <td><span class="badge badge-<?php echo $m['active'] ? 'active' : 'inactive'; ?>"><?php echo $m['active'] ? 'Ativo' : 'Inativo'; ?></span></td>
        <td class="actions">
          <a class="btn btn-sm btn-secondary" href="<?php echo e(admin_url('/admin/equipe/' . $m['id'] . '/editar')); ?>">Editar</a>
          <form method="post" action="<?php echo e(admin_url('/admin/equipe/' . $m['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover este membro da equipe?');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
