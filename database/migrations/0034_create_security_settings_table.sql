CREATE TABLE IF NOT EXISTS `security_settings` (
  `id`            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `setting_key`   VARCHAR(100)     NOT NULL,
  `setting_value` TEXT             NOT NULL DEFAULT '',
  `description`   VARCHAR(255)     NOT NULL DEFAULT '',
  `updated_by`    INT UNSIGNED     NULL     DEFAULT NULL,
  `created_at`    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_security_setting_key` (`setting_key`),
  CONSTRAINT `fk_ss_updated_by`
    FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default settings (all values are safe-by-default). admin_route_token is deliberately
-- NOT seeded here: SecuritySettings::createMissingDefaults() generates it via
-- random_bytes() the first time the app boots, so the table never ships with a blank
-- (i.e. permanently invalid) token.
INSERT IGNORE INTO `security_settings`
  (`setting_key`, `setting_value`, `description`) VALUES
  ('admin_session_timeout',      '120', 'Tempo máximo de inatividade da sessão (minutos)'),
  ('max_login_attempts',         '5',   'Número máximo de tentativas de login antes do bloqueio'),
  ('login_lock_minutes',         '15',  'Tempo de bloqueio após exceder tentativas (minutos)'),
  ('password_min_length',        '12',  'Comprimento mínimo da senha'),
  ('password_require_uppercase', '1',   'Exigir letra maiúscula na senha (1=sim, 0=não)'),
  ('password_require_lowercase', '1',   'Exigir letra minúscula na senha (1=sim, 0=não)'),
  ('password_require_number',    '1',   'Exigir número na senha (1=sim, 0=não)'),
  ('password_require_special',   '1',   'Exigir caractere especial na senha (1=sim, 0=não)'),
  ('enable_mfa',                 '0',   'Habilitar autenticação de dois fatores (1=sim, 0=não)'),
  ('allow_multiple_sessions',    '1',   'Permitir múltiplas sessões simultâneas (1=sim, 0=não)'),
  ('remember_login_days',        '30',  'Duração do "lembrar login" em dias'),
  ('csrf_enabled',               '1',   'Habilitar proteção CSRF em todos os formulários (1=sim, 0=não)'),
  ('session_regenerate_login',   '1',   'Regenerar ID de sessão no login (1=sim, 0=não)'),
  ('audit_enabled',              '1',   'Habilitar log de auditoria (1=sim, 0=não)'),
  ('maintenance_mode',           '0',   'Modo de manutenção ativo (1=sim, 0=não)'),
  ('maintenance_message',        'Site temporariamente em manutenção. Volte em breve.', 'Mensagem exibida durante manutenção'),
  ('force_https',                '0',   'Forçar redirecionamento HTTP→HTTPS (1=sim, 0=não)'),
  ('same_site_cookie',           'Lax', 'Política SameSite dos cookies de sessão (Strict/Lax/None)'),
  ('cookie_secure',              '0',   'Marcar cookie de sessão como Secure (1=sim, 0=não)'),
  ('cookie_http_only',           '1',   'Marcar cookie de sessão como HttpOnly (1=sim, 0=não)'),
  ('log_retention_days',         '90',  'Retenção de logs de auditoria em dias (0=indefinido)'),
  ('allow_admin_registration',   '0',   'Permitir auto-cadastro de administradores (1=sim, 0=não — não recomendado)'),
  ('enable_security_headers',    '1',   'Habilitar cabeçalhos de segurança HTTP via middleware (1=sim, 0=não)');
