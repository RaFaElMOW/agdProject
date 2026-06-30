<?php

namespace App\Services;

use App\Repositories\LoginAttemptRepository;
use App\Repositories\UserRepository;
use App\Security\LoginProtection;

class AuthService
{
    private UserRepository $users;
    private LoginAttemptRepository $attempts;
    private AuditService $audit;
    private LoginProtection $loginProtection;

    public function __construct(
        ?UserRepository $users = null,
        ?LoginAttemptRepository $attempts = null,
        ?AuditService $audit = null,
        ?LoginProtection $loginProtection = null
    ) {
        $this->users = $users ?? new UserRepository();
        $this->attempts = $attempts ?? new LoginAttemptRepository();
        $this->audit = $audit ?? new AuditService();
        $this->loginProtection = $loginProtection ?? new LoginProtection();
    }

    /**
     * @return array{ok: bool, message?: string, user?: array, must_change_password?: bool}
     */
    public function login(string $email, string $password, string $ip, string $userAgent): array
    {
        $email = mb_strtolower(trim($email));
        $user = $this->users->findByEmail($email);
        $genericError = 'E-mail ou senha inválidos.';

        if ($user === null) {
            $this->attempts->record($email, false, $ip, $userAgent);
            return ['ok' => false, 'message' => $genericError];
        }

        if (!empty($user['locked_until']) && strtotime($user['locked_until']) > time()) {
            $this->attempts->record($email, false, $ip, $userAgent);
            $this->audit->log((int) $user['id'], 'login_blocked_lockout', $ip);
            return ['ok' => false, 'message' => 'Conta temporariamente bloqueada por excesso de tentativas. Tente novamente mais tarde.'];
        }

        if ($user['status'] !== 'active') {
            $this->attempts->record($email, false, $ip, $userAgent);
            $this->audit->log((int) $user['id'], 'login_blocked_status', $ip, 'user', (string) $user['id'], ['status' => $user['status']]);
            return ['ok' => false, 'message' => $genericError];
        }

        if (!password_verify($password, $user['password_hash'])) {
            $this->users->incrementFailedAttempts((int) $user['id']);
            $this->attempts->record($email, false, $ip, $userAgent);

            $maxAttempts = $this->loginProtection->maxAttempts();
            if (((int) $user['failed_attempts']) + 1 >= $maxAttempts) {
                $this->users->lockUntil((int) $user['id'], $this->loginProtection->lockUntilDatetime());
                $this->audit->log((int) $user['id'], 'account_locked', $ip, 'user', (string) $user['id']);
            }

            $this->audit->log((int) $user['id'], 'login_failed', $ip, 'user', (string) $user['id']);
            return ['ok' => false, 'message' => $genericError];
        }

        $this->users->resetFailedAttempts((int) $user['id']);
        $this->users->touchLastLogin((int) $user['id']);
        $this->attempts->record($email, true, $ip, $userAgent);
        $this->audit->log((int) $user['id'], 'login_success', $ip, 'user', (string) $user['id']);

        if ((new \App\Security\SessionManager())->regenerateOnLogin()) {
            session_regenerate_id(true);
        }
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['token_version'] = (int) $user['token_version'];
        $_SESSION['last_activity'] = time();

        return [
            'ok' => true,
            'user' => $user,
            'must_change_password' => (bool) $user['must_change_password'],
        ];
    }

    public function logout(string $ip): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId !== null) {
            $this->audit->log((int) $userId, 'logout', $ip, 'user', (string) $userId);
        }
        $_SESSION = [];
        session_unset();
        session_destroy();
    }

    public function logoutGlobal(int $userId, string $ip): void
    {
        $this->users->bumpTokenVersion($userId);
        $this->audit->log($userId, 'logout_global', $ip, 'user', (string) $userId);
        $_SESSION = [];
        session_unset();
        session_destroy();
    }
}
