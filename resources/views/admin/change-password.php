<h1>Troca de senha obrigatória</h1>
<?php if (!empty($error)): ?>
  <div class="error"><?php echo e($error); ?></div>
<?php endif; ?>
<p>Por segurança, defina uma nova senha antes de continuar.</p>
<form method="post" action="<?php echo e(admin_url('/admin/trocar-senha')); ?>">
  <?php echo csrf_field(); ?>
  <label for="password">Nova senha (mínimo 10 caracteres)</label>
  <input type="password" id="password" name="password" minlength="10" required>
  <label for="password_confirmation">Confirme a nova senha</label>
  <input type="password" id="password_confirmation" name="password_confirmation" minlength="10" required>
  <button type="submit">Salvar nova senha</button>
</form>
