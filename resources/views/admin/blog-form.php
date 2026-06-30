<h1><?php echo $post ? 'Editar post' : 'Novo post'; ?></h1>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<link rel="stylesheet" href="<?php echo e(admin_url('/admin/assets/quill/quill.snow.css')); ?>">

<form method="post" action="<?php echo e($post ? admin_url('/admin/blog/' . $post['id']) : admin_url('/admin/blog')); ?>" enctype="multipart/form-data" id="blog-form">
  <?php echo csrf_field(); ?>

  <div class="grid-2">
    <div class="field"><label for="title">Título</label><input type="text" id="title" name="title" value="<?php echo e($post['title'] ?? ''); ?>" required></div>
    <div class="field"><label for="slug">Slug (URL, opcional)</label><input type="text" id="slug" name="slug" value="<?php echo e($post['slug'] ?? ''); ?>"></div>
  </div>

  <div class="field"><label for="excerpt">Resumo (exibido na listagem)</label><textarea id="excerpt" name="excerpt"><?php echo e($post['excerpt'] ?? ''); ?></textarea></div>

  <div class="field">
    <label for="content-editor">Conteúdo</label>
    <div class="hint">Formatação disponível: títulos, negrito, itálico, sublinhado, tachado, listas, citação, link e imagem (por URL). Qualquer outro HTML colado aqui é removido automaticamente ao salvar.</div>
    <div id="content-editor" style="min-height:260px; background:#fff;"><?php echo $post['content'] ?? ''; /* already sanitized on save; Quill parses this as its initial content */ ?></div>
    <textarea id="content" name="content" style="display:none;"></textarea>
  </div>

  <div class="field">
    <label for="banner">Banner</label>
    <?php if (!empty($post['banner'])): ?><div class="hint">Atual: <?php echo e($post['banner']); ?></div><?php endif; ?>
    <input type="file" id="banner" name="banner" accept="image/jpeg,image/png">
  </div>

  <div class="grid-2">
    <div class="field">
      <label for="category_id">Categoria</label>
      <select id="category_id" name="category_id">
        <option value="">— Nenhuma —</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?php echo (int) $cat['id']; ?>" <?php echo (int) ($post['category_id'] ?? 0) === (int) $cat['id'] ? 'selected' : ''; ?>><?php echo e($cat['name']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field"><label for="tags">Tags (separadas por vírgula)</label><input type="text" id="tags" name="tags" value="<?php echo e($tags); ?>"></div>
  </div>

  <div class="grid-2">
    <div class="field">
      <label for="status">Status</label>
      <select id="status" name="status" onchange="document.getElementById('published_at_field').style.display = (this.value === 'draft') ? 'none' : 'block';">
        <option value="draft" <?php echo ($post['status'] ?? 'draft') === 'draft' ? 'selected' : ''; ?>>Rascunho</option>
        <option value="scheduled" <?php echo ($post['status'] ?? '') === 'scheduled' ? 'selected' : ''; ?>>Agendado</option>
        <option value="published" <?php echo ($post['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Publicado</option>
      </select>
    </div>
    <div class="field" id="published_at_field" style="<?php echo ($post['status'] ?? 'draft') === 'draft' ? 'display:none;' : ''; ?>">
      <label for="published_at">Data/hora de publicação</label>
      <input type="datetime-local" id="published_at" name="published_at" value="<?php echo e(!empty($post['published_at']) ? str_replace(' ', 'T', substr($post['published_at'], 0, 16)) : ''); ?>">
    </div>
  </div>

  <fieldset>
    <legend>SEO</legend>
    <div class="field"><label for="meta_title">Meta título</label><input type="text" id="meta_title" name="meta_title" value="<?php echo e($post['meta_title'] ?? ''); ?>"></div>
    <div class="field"><label for="meta_description">Meta descrição</label><textarea id="meta_description" name="meta_description"><?php echo e($post['meta_description'] ?? ''); ?></textarea></div>
    <div class="field">
      <label for="og_image">Imagem Open Graph</label>
      <?php if (!empty($post['og_image'])): ?><div class="hint">Atual: <?php echo e($post['og_image']); ?></div><?php endif; ?>
      <input type="file" id="og_image" name="og_image" accept="image/jpeg,image/png">
    </div>
  </fieldset>

  <button type="submit">Salvar</button>
</form>

<script src="<?php echo e(admin_url('/admin/assets/quill/quill.min.js')); ?>"></script>
<script>
(function () {
  // Toolbar is deliberately limited to exactly what the server-side sanitizer allows
  // (see App\Support\Sanitizer) — no color/background/align/font, since those rely on
  // inline styles that the sanitizer strips (and won't survive a save anyway).
  var quill = new Quill('#content-editor', {
    theme: 'snow',
    modules: {
      toolbar: [
        [{ header: 2 }, { header: 3 }, { header: 4 }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['blockquote', 'link', 'image'],
        ['clean']
      ]
    }
  });

  var form = document.getElementById('blog-form');
  var hidden = document.getElementById('content');

  form.addEventListener('submit', function () {
    // The editor's HTML is a UX convenience only — the server independently sanitizes
    // this value on save (see App\Support\Sanitizer::richText), so this never needs to
    // be trusted client-side.
    hidden.value = quill.root.innerHTML;
  });
})();
</script>
