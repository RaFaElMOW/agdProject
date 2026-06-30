<h1><?php echo $user ? 'Editar usuário' : 'Novo usuário'; ?></h1>

<form method="post" action="<?php echo e($user ? admin_url('/admin/usuarios/' . $user['id']) : admin_url('/admin/usuarios')); ?>">
  <?php echo csrf_field(); ?>

  <div class="field">
    <label for="name">Nome</label>
    <input type="text" id="name" name="name" value="<?php echo e($user['name'] ?? ''); ?>" required>
  </div>

  <div class="field">
    <label for="email">E-mail</label>
    <input type="email" id="email" name="email" value="<?php echo e($user['email'] ?? ''); ?>" required>
  </div>

  <?php if (!$user): ?>
  <div class="field">
    <label for="password">Senha inicial (mínimo <?php echo e((string) $minLength); ?> caracteres)</label>
    <input type="password" id="password" name="password" minlength="<?php echo e((string) $minLength); ?>" required>
  </div>
  <div class="hint">O usuário será obrigado a trocar a senha no primeiro login.</div>
  <?php else: ?>
  <div class="field">
    <label for="status">Status</label>
    <select id="status" name="status">
      <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Ativo</option>
      <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inativo</option>
      <option value="blocked" <?php echo $user['status'] === 'blocked' ? 'selected' : ''; ?>>Bloqueado</option>
    </select>
  </div>
  <?php endif; ?>

  <div class="field">
    <label>Perfis</label>
    <?php foreach ($roles as $role): ?>
      <label style="display:inline-block; margin-right: 1rem; font-weight: normal;">
        <input type="checkbox" name="roles[]" value="<?php echo (int) $role['id']; ?>" <?php echo in_array((int) $role['id'], $selectedRoleIds, true) ? 'checked' : ''; ?>>
        <?php echo e($role['name']); ?>
      </label>
    <?php endforeach; ?>
  </div>

  <button type="submit">Salvar</button>
</form>
