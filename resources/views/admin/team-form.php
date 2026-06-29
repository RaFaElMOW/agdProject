<h1><?php echo $member ? 'Editar membro da equipe' : 'Novo membro da equipe'; ?></h1>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<form method="post" action="<?php echo e($member ? admin_url('/admin/equipe/' . $member['id']) : admin_url('/admin/equipe')); ?>" enctype="multipart/form-data">
  <?php echo csrf_field(); ?>

  <div class="grid-2">
    <div class="field"><label for="name">Nome</label><input type="text" id="name" name="name" value="<?php echo e($member['name'] ?? ''); ?>" required></div>
    <div class="field"><label for="role">Cargo / Posição</label><input type="text" id="role" name="role" value="<?php echo e($member['role'] ?? ''); ?>"></div>
  </div>

  <div class="field">
    <label for="photo">Foto</label>
    <?php if (!empty($member['photo'])): ?><div class="hint">Atual: <?php echo e($member['photo']); ?></div><?php endif; ?>
    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png">
  </div>

  <div class="field"><label for="bio">Biografia</label><textarea id="bio" name="bio"><?php echo e($member['bio'] ?? ''); ?></textarea></div>

  <div class="grid-2">
    <div class="field"><label for="facebook">Facebook</label><input type="text" id="facebook" name="facebook" value="<?php echo e($member['facebook'] ?? ''); ?>"></div>
    <div class="field"><label for="instagram">Instagram</label><input type="text" id="instagram" name="instagram" value="<?php echo e($member['instagram'] ?? ''); ?>"></div>
    <div class="field"><label for="twitter">Twitter/X</label><input type="text" id="twitter" name="twitter" value="<?php echo e($member['twitter'] ?? ''); ?>"></div>
    <div class="field"><label for="linkedin">LinkedIn</label><input type="text" id="linkedin" name="linkedin" value="<?php echo e($member['linkedin'] ?? ''); ?>"></div>
  </div>

  <div class="grid-2">
    <div class="field"><label for="sort_order">Ordem</label><input type="text" id="sort_order" name="sort_order" value="<?php echo e((string) ($member['sort_order'] ?? 0)); ?>"></div>
    <div class="field"><label><input type="checkbox" name="active" value="1" <?php echo ($member['active'] ?? 1) ? 'checked' : ''; ?>> Ativo (visível no site)</label></div>
  </div>

  <button type="submit">Salvar</button>
</form>
