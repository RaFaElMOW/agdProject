<h1>Blog</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<p>
  <a href="<?php echo e(admin_url('/admin/blog/novo')); ?>" class="btn">+ Novo post</a>
  <a href="<?php echo e(admin_url('/admin/blog/categorias')); ?>" class="btn btn-secondary">Categorias</a>
  <a href="<?php echo e(admin_url('/admin/blog/comentarios')); ?>" class="btn btn-secondary">Comentários</a>
</p>

<table>
  <thead><tr><th>Título</th><th>Categoria</th><th>Status</th><th>Publicação</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($posts as $p): ?>
      <tr>
        <td><?php echo e($p['title']); ?></td>
        <td><?php echo e($p['category_name'] ?? '—'); ?></td>
        <td><span class="badge badge-<?php echo $p['status'] === 'published' ? 'active' : 'inactive'; ?>"><?php echo e($p['status']); ?></span></td>
        <td><?php echo e($p['published_at'] ?? '—'); ?></td>
        <td class="actions">
          <a class="btn btn-sm btn-secondary" href="<?php echo e('/blog/' . $p['slug']); ?>" target="_blank">Ver</a>
          <a class="btn btn-sm btn-secondary" href="<?php echo e(admin_url('/admin/blog/' . $p['id'] . '/editar')); ?>">Editar</a>
          <form method="post" action="<?php echo e(admin_url('/admin/blog/' . $p['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover este post?');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
