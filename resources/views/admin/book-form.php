<h1><?php echo $book ? 'Editar livro' : 'Novo livro'; ?></h1>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<form method="post" action="<?php echo e($book ? admin_url('/admin/livros/' . $book['id']) : admin_url('/admin/livros')); ?>" enctype="multipart/form-data">
  <?php echo csrf_field(); ?>

  <div class="grid-2">
    <div class="field"><label for="title">Título</label><input type="text" id="title" name="title" value="<?php echo e($book['title'] ?? ''); ?>" required></div>
    <div class="field"><label for="author">Autor</label><input type="text" id="author" name="author" value="<?php echo e($book['author'] ?? ''); ?>"></div>
  </div>

  <div class="field"><label for="description">Descrição</label><textarea id="description" name="description"><?php echo e($book['description'] ?? ''); ?></textarea></div>

  <div class="field">
    <label for="cover">Capa</label>
    <?php if (!empty($book['cover'])): ?><div class="hint">Atual: <?php echo e($book['cover']); ?></div><?php endif; ?>
    <input type="file" id="cover" name="cover" accept="image/jpeg,image/png">
  </div>

  <div class="field"><label for="link">Link de compra (mailto: ou URL)</label><input type="text" id="link" name="link" value="<?php echo e($book['link'] ?? ''); ?>"></div>

  <div class="grid-2">
    <div class="field"><label for="price">Preço</label><input type="text" id="price" name="price" value="<?php echo e((string) ($book['price'] ?? '')); ?>" placeholder="50.00"></div>
    <div class="field"><label for="currency">Moeda</label><input type="text" id="currency" name="currency" value="<?php echo e($book['currency'] ?? ''); ?>" placeholder="BRL, USD..."></div>
    <div class="field"><label for="format">Formato</label><input type="text" id="format" name="format" value="<?php echo e($book['format'] ?? ''); ?>" placeholder="Físico, E-book, Kindle..."></div>
  </div>

  <div class="grid-2">
    <div class="field"><label for="sort_order">Ordem</label><input type="text" id="sort_order" name="sort_order" value="<?php echo e((string) ($book['sort_order'] ?? 0)); ?>"></div>
    <div class="field">
      <label for="status">Status</label>
      <select id="status" name="status">
        <option value="active" <?php echo ($book['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Ativo</option>
        <option value="inactive" <?php echo ($book['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inativo</option>
      </select>
    </div>
  </div>

  <button type="submit">Salvar</button>
</form>
