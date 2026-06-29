<h1>Usuários</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>
<?php if (!empty($generatedPassword)): ?>
  <div class="success">Nova senha temporária: <strong><?php echo e($generatedPassword); ?></strong> — copie agora, ela não será exibida novamente.</div>
<?php endif; ?>

<p><a href="<?php echo e(admin_url('/admin/usuarios/novo')); ?>" class="btn">+ Novo usuário</a></p>

<table>
  <thead><tr><th>Nome</th><th>E-mail</th><th>Perfis</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?php echo e($u['name']); ?></td>
        <td><?php echo e($u['email']); ?></td>
        <td><?php echo e(implode(', ', $u['role_names']) ?: '—'); ?></td>
        <td><span class="badge badge-<?php echo e($u['status']); ?>"><?php echo e($u['status']); ?></span></td>
        <td class="actions">
          <a class="btn btn-sm btn-secondary" href="<?php echo e(admin_url('/admin/usuarios/' . $u['id'] . '/editar')); ?>">Editar</a>
          <form method="post" action="<?php echo e(admin_url('/admin/usuarios/' . $u['id'] . '/resetar-senha')); ?>" onsubmit="return confirm('Gerar nova senha temporária para este usuário?');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-secondary">Resetar senha</button>
          </form>
          <?php if ($u['status'] === 'active'): ?>
          <form method="post" action="<?php echo e(admin_url('/admin/usuarios/' . $u['id'] . '/status')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="status" value="blocked">
            <button type="submit" class="btn btn-sm btn-danger">Bloquear</button>
          </form>
          <?php else: ?>
          <form method="post" action="<?php echo e(admin_url('/admin/usuarios/' . $u['id'] . '/status')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="status" value="active">
            <button type="submit" class="btn btn-sm">Ativar</button>
          </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
