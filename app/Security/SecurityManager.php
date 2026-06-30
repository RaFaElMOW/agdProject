<?php

declare(strict_types=1);

namespace App\Security;

/**
 * Facade that wires together the Security Settings sub-components
 * (SecuritySettings, RouteManager, SessionManager, LoginProtection, AuditLogger)
 * so the rest of the application has a single, simple entry point.
 */
class SecurityManager
{
    private static ?self $instance = null;

    private SecuritySettings $settings;
    private RouteManager $routes;
    private SessionManager $session;
    private LoginProtection $loginProtection;
    private AuditLogger $audit;

    private function __construct()
    {
        $this->settings = SecuritySettings::getInstance();
        $this->routes = new RouteManager($this->settings);
        $this->session = new SessionManager($this->settings);
        $this->loginProtection = new LoginProtection($this->settings);
        $this->audit = new AuditLogger(null, $this->settings);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function settings(): SecuritySettings
    {
        return $this->settings;
    }

    public function routes(): RouteManager
    {
        return $this->routes;
    }

    public function session(): SessionManager
    {
        return $this->session;
    }

    public function loginProtection(): LoginProtection
    {
        return $this->loginProtection;
    }

    public function audit(): AuditLogger
    {
        return $this->audit;
    }

    public function isMaintenanceMode(): bool
    {
        return $this->settings->getBool('maintenance_mode');
    }

    public function maintenanceMessage(): string
    {
        return $this->settings->get('maintenance_message');
    }

    public function forceHttps(): bool
    {
        return $this->settings->getBool('force_https');
    }

    public function securityHeadersEnabled(): bool
    {
        return $this->settings->getBool('enable_security_headers');
    }
}
