<h1>Auditoria</h1>

<table>
  <thead><tr><th>Data</th><th>Usuário</th><th>Ação</th><th>Entidade</th><th>IP</th></tr></thead>
  <tbody>
    <?php foreach ($logs as $log): ?>
      <tr>
        <td><?php echo e($log['created_at']); ?></td>
        <td><?php echo e($log['user_name'] ?? '—'); ?></td>
        <td><?php echo e($log['action']); ?></td>
        <td><?php echo e(trim(($log['entity_type'] ?? '') . ' ' . ($log['entity_id'] ?? ''))) ?: '—'; ?></td>
        <td><?php echo e($log['ip']); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<p>
  <?php if ($page > 1): ?><a class="btn btn-sm btn-secondary" href="<?php echo e(admin_url('/admin/auditoria?page=' . ($page - 1))); ?>">&laquo; Anterior</a><?php endif; ?>
  Página <?php echo (int) $page; ?> de <?php echo (int) $totalPages; ?>
  <?php if ($page < $totalPages): ?><a class="btn btn-sm btn-secondary" href="<?php echo e(admin_url('/admin/auditoria?page=' . ($page + 1))); ?>">Próxima &raquo;</a><?php endif; ?>
</p>
