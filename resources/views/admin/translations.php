<h1>Traduções</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<div class="hint">Edite o texto que aparece no site para cada idioma. Deixe o campo igual ao padrão (ou vazio) para voltar ao texto original. <?php echo count($rows); ?> textos neste idioma.</div>

<p>
  <?php foreach ($languages as $code => $info): ?>
    <a href="<?php echo e(admin_url('/admin/traducoes?lang=' . $code)); ?>" class="btn btn-sm <?php echo $code === $currentLang ? '' : 'btn-secondary'; ?>"><?php echo e($info['label']); ?></a>
  <?php endforeach; ?>
</p>

<div class="field">
  <label for="translation-search">Buscar (por chave ou texto)</label>
  <input type="text" id="translation-search" placeholder="Digite para filtrar..." style="max-width:480px;">
</div>

<form method="post" action="<?php echo e(admin_url('/admin/traducoes')); ?>">
  <?php echo csrf_field(); ?>
  <input type="hidden" name="lang" value="<?php echo e($currentLang); ?>">

  <table id="translations-table">
    <thead><tr><th style="width:22%;">Chave</th><th style="width:28%;">Padrão</th><th>Texto atual</th><th style="width:70px;">Status</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $row): ?>
        <tr class="translation-row">
          <td><code style="font-size:.78rem;"><?php echo e($row['key']); ?></code></td>
          <td><span class="hint" style="margin:0;"><?php echo e($row['default']); ?></span></td>
          <td><textarea name="values[<?php echo e($row['key']); ?>]" rows="2" style="width:100%; max-width:none;"><?php echo e($row['current']); ?></textarea></td>
          <td><?php if ($row['overridden']): ?><span class="badge badge-active">editado</span><?php else: ?><span class="badge badge-inactive">padrão</span><?php endif; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <button type="submit">Salvar traduções</button>
</form>

<script>
document.getElementById('translation-search').addEventListener('input', function () {
  var term = this.value.toLowerCase();
  document.querySelectorAll('#translations-table .translation-row').forEach(function (row) {
    row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
  });
});
</script>
