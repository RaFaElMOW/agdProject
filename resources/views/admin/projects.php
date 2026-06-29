<h1>Projetos</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<p><a href="<?php echo e(admin_url('/admin/projetos/novo')); ?>" class="btn">+ Novo projeto</a></p>

<table>
  <thead><tr><th>Ordem</th><th>Nome</th><th>Slug</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($projects as $p): ?>
      <tr>
        <td><?php echo (int) $p['sort_order']; ?></td>
        <td><?php echo e($p['name']); ?></td>
        <td><?php echo e($p['slug']); ?></td>
        <td><span class="badge badge-<?php echo $p['status'] === 'active' ? 'active' : 'inactive'; ?>"><?php echo e($p['status']); ?></span></td>
        <td class="actions">
          <a class="btn btn-sm btn-secondary" href="<?php echo e('/projeto.php?slug=' . $p['slug']); ?>" target="_blank">Ver</a>
          <a class="btn btn-sm btn-secondary" href="<?php echo e(admin_url('/admin/projetos/' . $p['id'] . '/editar')); ?>">Editar</a>
          <form method="post" action="<?php echo e(admin_url('/admin/projetos/' . $p['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover este projeto?');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
