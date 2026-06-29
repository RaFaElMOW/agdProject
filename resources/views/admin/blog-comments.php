<h1>Comentários do Blog</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>

<table>
  <thead><tr><th>Post</th><th>Autor</th><th>Comentário</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($comments as $c): ?>
      <tr>
        <td><a href="<?php echo e('/blog/' . $c['post_slug']); ?>" target="_blank"><?php echo e($c['post_title']); ?></a></td>
        <td><?php echo e($c['author_name']); ?><br><small><?php echo e($c['author_email']); ?></small></td>
        <td><?php echo e(mb_strimwidth($c['content'], 0, 140, '...')); ?></td>
        <td><span class="badge badge-<?php echo $c['status'] === 'approved' ? 'active' : ($c['status'] === 'spam' ? 'blocked' : 'inactive'); ?>"><?php echo e($c['status']); ?></span></td>
        <td class="actions">
          <?php if ($c['status'] !== 'approved'): ?>
          <form method="post" action="<?php echo e(admin_url('/admin/blog/comentarios/' . $c['id'] . '/aprovar')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm">Aprovar</button>
          </form>
          <?php endif; ?>
          <?php if ($c['status'] !== 'spam'): ?>
          <form method="post" action="<?php echo e(admin_url('/admin/blog/comentarios/' . $c['id'] . '/spam')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-secondary">Spam</button>
          </form>
          <?php endif; ?>
          <form method="post" action="<?php echo e(admin_url('/admin/blog/comentarios/' . $c['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover este comentário?');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<p><a href="<?php echo e(admin_url('/admin/blog')); ?>">&laquo; Voltar para posts</a></p>
