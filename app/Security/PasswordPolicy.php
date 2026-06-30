<?php

declare(strict_types=1);

namespace App\Security;

/**
 * Enforces the password rules configured in Security Settings (length + character
 * classes) and generates compliant, high-entropy passwords for admin-triggered resets.
 */
class PasswordPolicy
{
    private const CHARS_LOWER = 'abcdefghijklmnopqrstuvwxyz';
    private const CHARS_UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const CHARS_NUMBER = '0123456789';
    private const CHARS_SPECIAL = '!@#$%^&*()-_=+[]{}';

    private SecuritySettings $settings;

    public function __construct(?SecuritySettings $settings = null)
    {
        $this->settings = $settings ?? SecuritySettings::getInstance();
    }

    public function minLength(): int
    {
        return $this->settings->getInt('password_min_length');
    }

    /**
     * Returns a list of validation error messages (empty array = password is valid).
     */
    public function validate(string $password): array
    {
        $errors = [];

        if (mb_strlen($password) < $this->minLength()) {
            $errors[] = "A senha deve ter no mínimo {$this->minLength()} caracteres.";
        }
        if ($this->settings->getBool('password_require_uppercase') && !preg_match('/[A-Z]/', $password)) {
            $errors[] = 'A senha deve conter ao menos uma letra maiúscula.';
        }
        if ($this->settings->getBool('password_require_lowercase') && !preg_match('/[a-z]/', $password)) {
            $errors[] = 'A senha deve conter ao menos uma letra minúscula.';
        }
        if ($this->settings->getBool('password_require_number') && !preg_match('/[0-9]/', $password)) {
            $errors[] = 'A senha deve conter ao menos um número.';
        }
        if ($this->settings->getBool('password_require_special') && !preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = 'A senha deve conter ao menos um caractere especial.';
        }

        return $errors;
    }

    public function isValid(string $password): bool
    {
        return $this->validate($password) === [];
    }

    /**
     * Generates a random password satisfying every enabled rule, using random_int()
     * (CSPRNG-backed) for both character selection and shuffling — never rand()/mt_rand().
     */
    public function generateSecurePassword(): string
    {
        $length = max($this->minLength(), 16);

        $pools = [self::CHARS_LOWER];
        if ($this->settings->getBool('password_require_uppercase')) {
            $pools[] = self::CHARS_UPPER;
        }
        if ($this->settings->getBool('password_require_number')) {
            $pools[] = self::CHARS_NUMBER;
        }
        if ($this->settings->getBool('password_require_special')) {
            $pools[] = self::CHARS_SPECIAL;
        }

        $password = [];
        foreach ($pools as $pool) {
            $password[] = $pool[random_int(0, strlen($pool) - 1)];
        }

        $allChars = implode('', $pools);
        while (count($password) < $length) {
            $password[] = $allChars[random_int(0, strlen($allChars) - 1)];
        }

        for ($i = count($password) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$password[$i], $password[$j]] = [$password[$j], $password[$i]];
        }

        return implode('', $password);
    }
}
