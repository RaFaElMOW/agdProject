<?php

declare(strict_types=1);

namespace App\Security;

use App\Core\Database;

/**
 * Central, DB-backed security configuration store.
 *
 * Reads all rows from `security_settings` once per request (lazy, on first get()),
 * keeps them in an in-memory cache, and auto-creates missing keys with safe defaults
 * so the table is always in sync with the known setting catalogue.
 */
class SecuritySettings
{
    private static ?self $instance = null;
    private array $cache = [];
    private bool $loaded = false;

    private const DEFAULTS = [
        'admin_route_token'          => '',
        'admin_session_timeout'      => '120',
        'max_login_attempts'         => '5',
        'login_lock_minutes'         => '15',
        'password_min_length'        => '12',
        'password_require_uppercase' => '1',
        'password_require_lowercase' => '1',
        'password_require_number'    => '1',
        'password_require_special'   => '1',
        'enable_mfa'                 => '0',
        'allow_multiple_sessions'    => '1',
        'remember_login_days'        => '30',
        'csrf_enabled'               => '1',
        'session_regenerate_login'   => '1',
        'audit_enabled'              => '1',
        'maintenance_mode'           => '0',
        'maintenance_message'        => 'Site temporariamente em manutenção. Volte em breve.',
        'force_https'                => '0',
        'same_site_cookie'           => 'Lax',
        'cookie_secure'              => '0',
        'cookie_http_only'           => '1',
        'log_retention_days'         => '90',
        'allow_admin_registration'   => '0',
        'enable_security_headers'    => '1',
    ];

    private const DESCRIPTIONS = [
        'admin_route_token'          => 'Token secreto da URL administrativa (gerado automaticamente)',
        'admin_session_timeout'      => 'Tempo máximo de inatividade da sessão (minutos)',
        'max_login_attempts'         => 'Número máximo de tentativas de login antes do bloqueio',
        'login_lock_minutes'         => 'Tempo de bloqueio após exceder tentativas (minutos)',
        'password_min_length'        => 'Comprimento mínimo da senha',
        'password_require_uppercase' => 'Exigir letra maiúscula na senha',
        'password_require_lowercase' => 'Exigir letra minúscula na senha',
        'password_require_number'    => 'Exigir número na senha',
        'password_require_special'   => 'Exigir caractere especial na senha',
        'enable_mfa'                 => 'Habilitar autenticação de dois fatores',
        'allow_multiple_sessions'    => 'Permitir múltiplas sessões simultâneas',
        'remember_login_days'        => 'Duração do "lembrar login" em dias',
        'csrf_enabled'               => 'Habilitar proteção CSRF em todos os formulários',
        'session_regenerate_login'   => 'Regenerar ID de sessão no login',
        'audit_enabled'              => 'Habilitar log de auditoria',
        'maintenance_mode'           => 'Modo de manutenção ativo',
        'maintenance_message'        => 'Mensagem exibida durante manutenção',
        'force_https'                => 'Forçar redirecionamento HTTP→HTTPS',
        'same_site_cookie'           => 'Política SameSite dos cookies (Strict/Lax/None)',
        'cookie_secure'              => 'Marcar cookie como Secure (requer HTTPS)',
        'cookie_http_only'           => 'Marcar cookie como HttpOnly',
        'log_retention_days'         => 'Retenção de logs de auditoria em dias (0 = indefinido)',
        'allow_admin_registration'   => 'Permitir auto-cadastro no painel (não recomendado)',
        'enable_security_headers'    => 'Habilitar cabeçalhos de segurança HTTP',
    ];

    private function __construct() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get(string $key): string
    {
        $this->ensureLoaded();
        return $this->cache[$key] ?? self::DEFAULTS[$key] ?? '';
    }

    public function getBool(string $key): bool
    {
        return (bool) (int) $this->get($key);
    }

    public function getInt(string $key): int
    {
        return (int) $this->get($key);
    }

    /**
     * Persists a single setting and refreshes the in-memory cache.
     */
    public function set(string $key, string $value, ?int $updatedBy = null): void
    {
        try {
            $db = Database::connection();
            $stmt = $db->prepare(
                'INSERT INTO security_settings (setting_key, setting_value, description, updated_by)
                 VALUES (:key, :value, :desc, :by)
                 ON DUPLICATE KEY UPDATE
                   setting_value = VALUES(setting_value),
                   updated_by    = VALUES(updated_by),
                   updated_at    = NOW()'
            );
            $stmt->execute([
                'key'   => $key,
                'value' => $value,
                'desc'  => self::DESCRIPTIONS[$key] ?? '',
                'by'    => $updatedBy,
            ]);
        } catch (\Throwable $e) {
            error_log('[SecuritySettings] set() failed for key=' . $key . ': ' . $e->getMessage());
        }
        $this->cache[$key] = $value;
    }

    /**
     * Returns all settings as [key => value] for the admin UI.
     */
    public function all(): array
    {
        $this->ensureLoaded();
        $merged = self::DEFAULTS;
        foreach ($this->cache as $k => $v) {
            $merged[$k] = $v;
        }
        return $merged;
    }

    /**
     * Returns all settings as full rows (key, value, description) for the admin UI.
     */
    public function allWithMeta(): array
    {
        $this->ensureLoaded();
        $result = [];
        foreach (self::DEFAULTS as $key => $default) {
            $result[$key] = [
                'value'       => $this->cache[$key] ?? $default,
                'description' => self::DESCRIPTIONS[$key] ?? '',
                'default'     => $default,
            ];
        }
        return $result;
    }

    private function ensureLoaded(): void
    {
        if ($this->loaded) {
            return;
        }
        $this->loaded = true;

        try {
            $db = Database::connection();
            $rows = $db->query('SELECT setting_key, setting_value FROM security_settings')->fetchAll();
            foreach ($rows as $row) {
                $this->cache[$row['setting_key']] = $row['setting_value'];
            }
            $this->createMissingDefaults();
        } catch (\Throwable $e) {
            // DB not ready yet (migration not run); fall through to in-memory defaults.
            error_log('[SecuritySettings] Could not load from DB: ' . $e->getMessage());
        }
    }

    private function createMissingDefaults(): void
    {
        foreach (self::DEFAULTS as $key => $default) {
            $isMissing = !isset($this->cache[$key]);
            // admin_route_token additionally self-heals if it was ever seeded/cleared to
            // empty — an empty token can never validate (validateToken() requires >= 48
            // chars), so leaving it blank would permanently 404 the entire admin panel.
            $isBlankToken = $key === 'admin_route_token' && ($this->cache[$key] ?? '') === '';
            if ($isMissing || $isBlankToken) {
                $value = ($key === 'admin_route_token') ? $this->generateToken() : $default;
                $this->set($key, $value);
            }
        }
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(24)); // 48-char hex, 192 bits of entropy
    }
}
