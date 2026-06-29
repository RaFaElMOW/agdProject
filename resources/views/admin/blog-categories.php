<h1>Categorias do Blog</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<form method="post" action="<?php echo e(admin_url('/admin/blog/categorias')); ?>" style="margin-bottom:1.5rem;">
  <?php echo csrf_field(); ?>
  <div class="field"><label for="name">Nova categoria</label><input type="text" id="name" name="name" required></div>
  <button type="submit" class="btn">Adicionar</button>
</form>

<table>
  <thead><tr><th>Nome</th><th>Slug</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($categories as $c): ?>
      <tr>
        <td><?php echo e($c['name']); ?></td>
        <td><?php echo e($c['slug']); ?></td>
        <td>
          <form method="post" action="<?php echo e(admin_url('/admin/blog/categorias/' . $c['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover esta categoria?');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<p><a href="<?php echo e(admin_url('/admin/blog')); ?>">&laquo; Voltar para posts</a></p>
