<h1>Painel Administrativo</h1>
<?php if (!empty($error)): ?>
  <div class="error"><?php echo e($error); ?></div>
<?php endif; ?>
<form method="post" action="<?php echo e(admin_url('/admin/login')); ?>">
  <?php echo csrf_field(); ?>
  <label for="email">E-mail</label>
  <input type="email" id="email" name="email" required autofocus>
  <label for="password">Senha</label>
  <input type="password" id="password" name="password" required>
  <button type="submit">Entrar</button>
</form>
