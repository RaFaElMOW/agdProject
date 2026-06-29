<h1>Menus</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<p><a href="<?php echo e(admin_url('/admin/menus/novo')); ?>" class="btn">+ Novo item</a></p>

<?php
$byId = [];
foreach ($menus as $m) {
    $byId[$m['id']] = $m;
}
?>

<?php foreach (['header' => 'Cabeçalho (Header)', 'footer' => 'Rodapé (Footer)'] as $loc => $label): ?>
  <h3><?php echo e($label); ?></h3>
  <table>
    <thead><tr><th>Ordem</th><th>Rótulo</th><th>URL</th><th>Pai</th><th>Nova aba</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($menus as $m): if ($m['location'] !== $loc) continue; ?>
        <tr>
          <td><?php echo (int) $m['sort_order']; ?></td>
          <td><?php echo e($m['label']); ?></td>
          <td><?php echo e($m['url']); ?></td>
          <td><?php echo $m['parent_id'] && isset($byId[$m['parent_id']]) ? e($byId[$m['parent_id']]['label']) : '—'; ?></td>
          <td><?php echo $m['target_blank'] ? 'Sim' : 'Não'; ?></td>
          <td class="actions">
            <a class="btn btn-sm btn-secondary" href="<?php echo e(admin_url('/admin/menus/' . $m['id'] . '/editar')); ?>">Editar</a>
            <form method="post" action="<?php echo e(admin_url('/admin/menus/' . $m['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover este item de menu?');">
              <?php echo csrf_field(); ?>
              <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endforeach; ?>
