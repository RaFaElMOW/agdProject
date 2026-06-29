<h1><?php echo $project ? 'Editar projeto' : 'Novo projeto'; ?></h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<form method="post" action="<?php echo e($project ? admin_url('/admin/projetos/' . $project['id']) : admin_url('/admin/projetos')); ?>" enctype="multipart/form-data">
  <?php echo csrf_field(); ?>

  <div class="grid-2">
    <div class="field"><label for="name">Nome</label><input type="text" id="name" name="name" value="<?php echo e($project['name'] ?? ''); ?>" required></div>
    <div class="field"><label for="slug">Slug (URL, opcional — gerado automaticamente)</label><input type="text" id="slug" name="slug" value="<?php echo e($project['slug'] ?? ''); ?>"></div>
  </div>

  <div class="field"><label for="description">Descrição</label><textarea id="description" name="description"><?php echo e($project['description'] ?? ''); ?></textarea></div>

  <div class="field">
    <label for="banner">Banner / Imagem de capa</label>
    <?php if (!empty($project['banner'])): ?><div class="hint">Atual: <?php echo e($project['banner']); ?></div><?php endif; ?>
    <input type="file" id="banner" name="banner" accept="image/jpeg,image/png">
  </div>

  <div class="grid-2">
    <div class="field">
      <label for="status">Status</label>
      <select id="status" name="status">
        <option value="active" <?php echo ($project['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Ativo</option>
        <option value="paused" <?php echo ($project['status'] ?? '') === 'paused' ? 'selected' : ''; ?>>Pausado</option>
        <option value="completed" <?php echo ($project['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Concluído</option>
      </select>
    </div>
    <div class="field"><label for="sort_order">Ordem</label><input type="text" id="sort_order" name="sort_order" value="<?php echo e((string) ($project['sort_order'] ?? 0)); ?>"></div>
  </div>

  <div class="field"><label for="external_link">Link externo relacionado (opcional)</label><input type="url" id="external_link" name="external_link" value="<?php echo e($project['external_link'] ?? ''); ?>"></div>

  <button type="submit">Salvar</button>
</form>

<?php if ($project): ?>
<h3 style="margin-top:2rem;">Galeria</h3>
<div style="display:flex; flex-wrap:wrap; gap:.8rem; margin-bottom:1rem;">
  <?php foreach ($gallery as $img): ?>
    <div style="border:1px solid #eee; border-radius:6px; padding:.5rem; text-align:center;">
      <img src="<?php echo e(admin_url('/' . $img['image_path'])); ?>" alt="" style="width:120px; height:90px; object-fit:cover; display:block; margin-bottom:.4rem;">
      <form method="post" action="<?php echo e(admin_url('/admin/projetos/' . $project['id'] . '/galeria/' . $img['id'] . '/excluir')); ?>" onsubmit="return confirm('Remover esta imagem?');">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>
<form method="post" action="<?php echo e(admin_url('/admin/projetos/' . $project['id'] . '/galeria')); ?>" enctype="multipart/form-data">
  <?php echo csrf_field(); ?>
  <div class="field">
    <label for="image">Adicionar imagem à galeria</label>
    <input type="file" id="image" name="image" accept="image/jpeg,image/png" required>
  </div>
  <button type="submit" class="btn btn-secondary">Adicionar imagem</button>
</form>
<?php endif; ?>
