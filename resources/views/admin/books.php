<h1>Livros</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<p><a href="<?php echo e(admin_url('/admin/livros/novo')); ?>" class="btn">+ Novo livro</a></p>

<table>
  <thead><tr><th>Ordem</th><th>Título</th><th>Autor</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($books as $b): ?>
      <tr>
        <td><?php echo (int) $b['sort_order']; ?></td>
        <td><?php echo e($b['title']); ?></td>
        <td><?php echo e($b['author'] ?? ''); ?></td>
        <td><span class="badge badge-<?php echo $b['status'] === 'active' ? 'active' : 'inactive'; ?>"><?php echo e($b['status']); ?></span></td>
        <td class="actions">
          <a class="btn btn-sm btn-secondary" href="<?php echo e(admin_url('/admin/livros/' . $b['id'] . '/editar')); ?>">Editar</a>
          <form method="post" action="<?php echo e(admin_url('/admin/livros/' . $b['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover este livro?');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
