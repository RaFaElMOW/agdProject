<h1>Mídia</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<p><a href="<?php echo e(admin_url('/admin/midia/novo')); ?>" class="btn">+ Novo item</a></p>

<table>
  <thead><tr><th>Ordem</th><th>Tipo</th><th>Título</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($items as $m): ?>
      <tr>
        <td><?php echo (int) $m['sort_order']; ?></td>
        <td><?php echo $m['type'] === 'video' ? 'Vídeo' : 'Imagem'; ?></td>
        <td><?php echo e($m['title'] ?? ''); ?></td>
        <td><span class="badge badge-<?php echo $m['active'] ? 'active' : 'inactive'; ?>"><?php echo $m['active'] ? 'Ativo' : 'Inativo'; ?></span></td>
        <td class="actions">
          <a class="btn btn-sm btn-secondary" href="<?php echo e(admin_url('/admin/midia/' . $m['id'] . '/editar')); ?>">Editar</a>
          <form method="post" action="<?php echo e(admin_url('/admin/midia/' . $m['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover este item?');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
