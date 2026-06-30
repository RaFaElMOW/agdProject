<h1>Configurações de Segurança</h1>
<?php if (!empty($success)): ?><div class="success"><?php echo e($success); ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo e($error); ?></div><?php endif; ?>

<fieldset>
  <legend>URL administrativa</legend>
  <div class="hint">O painel não fica mais em <code>/admin</code> — esse caminho agora retorna 404. O acesso é feito por um endereço secreto, único e de alta entropia. Guarde-o nos favoritos.</div>
  <div class="field">
    <label for="currentPortalUrl">Endereço atual do painel</label>
    <input type="text" id="currentPortalUrl" value="<?php echo e($currentPortalUrl); ?>" readonly onclick="this.select();">
  </div>
  <form method="post" action="<?php echo e(admin_url('/admin/seguranca/regenerar-token')); ?>" data-confirm="Isso vai invalidar o endereço atual imediatamente e gerar um novo. Continuar?">
    <?php echo csrf_field(); ?>
    <button type="submit" class="btn btn-danger">Regenerar URL administrativa</button>
  </form>
</fieldset>

<form method="post" action="<?php echo e($adminUrl); ?>">
  <?php echo csrf_field(); ?>

  <fieldset>
    <legend>Sessão</legend>
    <div class="grid-2">
      <div class="field">
        <label for="admin_session_timeout">Tempo máximo de inatividade (minutos)</label>
        <input type="text" id="admin_session_timeout" name="admin_session_timeout" value="<?php echo e($settings['admin_session_timeout']); ?>">
      </div>
      <div class="field">
        <label for="remember_login_days">Duração do "lembrar login" (dias)</label>
        <input type="text" id="remember_login_days" name="remember_login_days" value="<?php echo e($settings['remember_login_days']); ?>">
      </div>
      <div class="field">
        <label for="same_site_cookie">Política SameSite dos cookies</label>
        <select id="same_site_cookie" name="same_site_cookie">
          <?php foreach (['Strict', 'Lax', 'None'] as $option): ?>
            <option value="<?php echo e($option); ?>"<?php echo $settings['same_site_cookie'] === $option ? ' selected' : ''; ?>><?php echo e($option); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="field">
      <label><input type="checkbox" name="session_regenerate_login" value="1"<?php echo $settings['session_regenerate_login'] === '1' ? ' checked' : ''; ?>> Regenerar ID de sessão no login</label>
    </div>
    <div class="field">
      <label><input type="checkbox" name="allow_multiple_sessions" value="1"<?php echo $settings['allow_multiple_sessions'] === '1' ? ' checked' : ''; ?>> Permitir múltiplas sessões simultâneas</label>
    </div>
    <div class="field">
      <label><input type="checkbox" name="cookie_secure" value="1"<?php echo $settings['cookie_secure'] === '1' ? ' checked' : ''; ?>> Cookie Secure (requer HTTPS)</label>
    </div>
    <div class="field">
      <label><input type="checkbox" name="cookie_http_only" value="1"<?php echo $settings['cookie_http_only'] === '1' ? ' checked' : ''; ?>> Cookie HttpOnly</label>
    </div>
  </fieldset>

  <fieldset>
    <legend>Login e bloqueio por força bruta</legend>
    <div class="grid-2">
      <div class="field">
        <label for="max_login_attempts">Tentativas máximas de login</label>
        <input type="text" id="max_login_attempts" name="max_login_attempts" value="<?php echo e($settings['max_login_attempts']); ?>">
      </div>
      <div class="field">
        <label for="login_lock_minutes">Duração do bloqueio (minutos)</label>
        <input type="text" id="login_lock_minutes" name="login_lock_minutes" value="<?php echo e($settings['login_lock_minutes']); ?>">
      </div>
    </div>
    <div class="field">
      <label><input type="checkbox" name="enable_mfa" value="1"<?php echo $settings['enable_mfa'] === '1' ? ' checked' : ''; ?>> Habilitar autenticação de dois fatores</label>
    </div>
    <div class="field">
      <label><input type="checkbox" name="allow_admin_registration" value="1"<?php echo $settings['allow_admin_registration'] === '1' ? ' checked' : ''; ?>> Permitir auto-cadastro no painel (não recomendado)</label>
    </div>
  </fieldset>

  <fieldset>
    <legend>Política de senha</legend>
    <div class="field">
      <label for="password_min_length">Comprimento mínimo</label>
      <input type="text" id="password_min_length" name="password_min_length" value="<?php echo e($settings['password_min_length']); ?>" style="max-width:160px;">
    </div>
    <div class="field">
      <label><input type="checkbox" name="password_require_uppercase" value="1"<?php echo $settings['password_require_uppercase'] === '1' ? ' checked' : ''; ?>> Exigir letra maiúscula</label>
    </div>
    <div class="field">
      <label><input type="checkbox" name="password_require_lowercase" value="1"<?php echo $settings['password_require_lowercase'] === '1' ? ' checked' : ''; ?>> Exigir letra minúscula</label>
    </div>
    <div class="field">
      <label><input type="checkbox" name="password_require_number" value="1"<?php echo $settings['password_require_number'] === '1' ? ' checked' : ''; ?>> Exigir número</label>
    </div>
    <div class="field">
      <label><input type="checkbox" name="password_require_special" value="1"<?php echo $settings['password_require_special'] === '1' ? ' checked' : ''; ?>> Exigir caractere especial</label>
    </div>
  </fieldset>

  <fieldset>
    <legend>Cabeçalhos, CSRF e HTTPS</legend>
    <div class="field">
      <label><input type="checkbox" name="enable_security_headers" value="1"<?php echo $settings['enable_security_headers'] === '1' ? ' checked' : ''; ?>> Habilitar cabeçalhos de segurança HTTP (CSP etc.)</label>
    </div>
    <div class="field">
      <label><input type="checkbox" name="csrf_enabled" value="1"<?php echo $settings['csrf_enabled'] === '1' ? ' checked' : ''; ?>> Habilitar proteção CSRF</label>
    </div>
    <div class="field">
      <label><input type="checkbox" name="force_https" value="1"<?php echo $settings['force_https'] === '1' ? ' checked' : ''; ?>> Forçar redirecionamento HTTP → HTTPS</label>
    </div>
  </fieldset>

  <fieldset>
    <legend>Auditoria</legend>
    <div class="field">
      <label><input type="checkbox" name="audit_enabled" value="1"<?php echo $settings['audit_enabled'] === '1' ? ' checked' : ''; ?>> Habilitar log de auditoria</label>
    </div>
    <div class="field">
      <label for="log_retention_days">Retenção de logs (dias — 0 = indefinido)</label>
      <input type="text" id="log_retention_days" name="log_retention_days" value="<?php echo e($settings['log_retention_days']); ?>" style="max-width:160px;">
    </div>
  </fieldset>

  <fieldset>
    <legend>Manutenção</legend>
    <div class="field">
      <label><input type="checkbox" name="maintenance_mode" value="1"<?php echo $settings['maintenance_mode'] === '1' ? ' checked' : ''; ?>> Ativar modo de manutenção (site público fora do ar)</label>
    </div>
    <div class="field">
      <label for="maintenance_message">Mensagem exibida durante manutenção</label>
      <textarea id="maintenance_message" name="maintenance_message"><?php echo e($settings['maintenance_message']); ?></textarea>
    </div>
  </fieldset>

  <button type="submit">Salvar configurações</button>
</form>
