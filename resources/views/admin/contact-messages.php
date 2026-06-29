<h1>Mensagens de Contato</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>

<table>
  <thead><tr><th>Data</th><th>Nome</th><th>E-mail</th><th>Assunto</th><th>Mensagem</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($messages as $m): ?>
      <tr>
        <td><?php echo e($m['created_at']); ?></td>
        <td><?php echo e($m['name']); ?></td>
        <td><?php echo e($m['email']); ?></td>
        <td><?php echo e($m['subject_option'] ?? ''); ?></td>
        <td><?php echo e(mb_strimwidth($m['message'], 0, 140, '...')); ?></td>
        <td><span class="badge badge-<?php echo $m['status'] === 'archived' ? 'inactive' : 'active'; ?>"><?php echo e($m['status']); ?></span></td>
        <td class="actions">
          <?php if ($m['status'] !== 'archived'): ?>
          <form method="post" action="<?php echo e(admin_url('/admin/mensagens/' . $m['id'] . '/arquivar')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-secondary">Arquivar</button>
          </form>
          <?php endif; ?>
          <form method="post" action="<?php echo e(admin_url('/admin/mensagens/' . $m['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover esta mensagem?');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
