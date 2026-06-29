<?php

declare(strict_types=1);

use App\Core\Database;

/**
 * Idempotent seeder: upserts the roles/permissions catalogue and creates the
 * first Administrator account only if no user exists yet. Returns a summary
 * array (and the generated password, only once) so tools/migrate.php can report it.
 */
function seed_roles_and_permissions(): array
{
    $db = Database::connection();
    $catalogue = require __DIR__ . '/roles_permissions.php';

    $insertPermission = $db->prepare(
        'INSERT INTO permissions (name, slug, module, created_at) VALUES (:name, :slug, :module, NOW())
         ON DUPLICATE KEY UPDATE name = VALUES(name), module = VALUES(module)'
    );
    foreach ($catalogue['permissions'] as $permission) {
        $insertPermission->execute($permission);
    }

    $permissionIds = [];
    foreach ($db->query('SELECT id, slug FROM permissions') as $row) {
        $permissionIds[$row['slug']] = (int) $row['id'];
    }

    $insertRole = $db->prepare(
        'INSERT INTO roles (name, slug, created_at) VALUES (:name, :slug, NOW())
         ON DUPLICATE KEY UPDATE name = VALUES(name)'
    );
    foreach ($catalogue['roles'] as $role) {
        $insertRole->execute(['name' => $role['name'], 'slug' => $role['slug']]);
    }

    $roleIds = [];
    foreach ($db->query('SELECT id, slug FROM roles') as $row) {
        $roleIds[$row['slug']] = (int) $row['id'];
    }

    $attachPermission = $db->prepare(
        'INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)'
    );
    foreach ($catalogue['roles'] as $role) {
        $roleId = $roleIds[$role['slug']];
        $slugs = $role['permissions'] === '*' ? array_keys($permissionIds) : $role['permissions'];
        foreach ($slugs as $slug) {
            if (!isset($permissionIds[$slug])) {
                continue;
            }
            $attachPermission->execute(['role_id' => $roleId, 'permission_id' => $permissionIds[$slug]]);
        }
    }

    return ['roles' => array_keys($roleIds), 'permissions' => array_keys($permissionIds)];
}

function seed_first_admin(): array
{
    $db = Database::connection();

    $count = (int) $db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($count > 0) {
        return ['created' => false, 'message' => 'Já existem usuários; nenhum admin foi criado.'];
    }

    $name = (string) env('ADMIN_NAME', 'Administrador');
    $email = mb_strtolower((string) env('ADMIN_EMAIL', 'admin@agdniger.com'));
    $password = (string) env('ADMIN_PASSWORD', '');
    $generated = false;

    if ($password === '') {
        $password = bin2hex(random_bytes(12));
        $generated = true;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $db->prepare(
        'INSERT INTO users (name, email, password_hash, status, must_change_password, token_version, created_at, updated_at)
         VALUES (:name, :email, :hash, \'active\', 1, 0, NOW(), NOW())'
    )->execute(['name' => $name, 'email' => $email, 'hash' => $hash]);

    $userId = (int) $db->lastInsertId();

    $roleId = (int) $db->query("SELECT id FROM roles WHERE slug = 'administrator' LIMIT 1")->fetchColumn();
    if ($roleId > 0) {
        $db->prepare('INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)')
            ->execute(['user_id' => $userId, 'role_id' => $roleId]);
    }

    return [
        'created' => true,
        'email' => $email,
        'password_shown_once' => $generated ? $password : null,
        'message' => $generated
            ? 'Admin criado com senha gerada automaticamente — copie agora, ela não será exibida novamente.'
            : 'Admin criado com a senha definida em ADMIN_PASSWORD.',
    ];
}

/**
 * Idempotent: settings are inserted only if the key doesn't exist yet (setIfMissing),
 * menus/content only on the very first run (checked via row counts) — re-running this
 * seed (e.g. when a later phase adds new default settings) never overwrites a value
 * an admin has already customized through the panel.
 */
function seed_settings_menus_and_content(): array
{
    $db = Database::connection();
    $defaults = require __DIR__ . '/settings_menus_content.php';

    $settingsRepo = new \App\Repositories\SettingsRepository();
    foreach ($defaults['settings'] as $key => [$value, $group]) {
        $settingsRepo->setIfMissing($key, $value, $group);
    }

    $menuCount = (int) $db->query('SELECT COUNT(*) FROM menus')->fetchColumn();
    $menusSeeded = false;
    if ($menuCount === 0) {
        $menuRepo = new \App\Repositories\MenuRepository();
        $parentIds = [];
        foreach ($defaults['menus'] as [$location, $label, $url, $parentLabel, $order, $blank]) {
            if ($parentLabel !== null) {
                continue;
            }
            $id = $menuRepo->create([
                'location' => $location, 'label' => $label, 'url' => $url,
                'parent_id' => null, 'sort_order' => $order, 'target_blank' => $blank,
            ]);
            $parentIds[$label] = $id;
        }
        foreach ($defaults['menus'] as [$location, $label, $url, $parentLabel, $order, $blank]) {
            if ($parentLabel === null) {
                continue;
            }
            $menuRepo->create([
                'location' => $location, 'label' => $label, 'url' => $url,
                'parent_id' => $parentIds[$parentLabel] ?? null, 'sort_order' => $order, 'target_blank' => $blank,
            ]);
        }
        $menusSeeded = true;
    }

    return [
        'settings' => array_keys($defaults['settings']),
        'menus_seeded' => $menusSeeded,
    ];
}

function seed_donation_methods(): array
{
    $db = Database::connection();
    $defaults = require __DIR__ . '/donation_methods.php';

    $methodsSeeded = false;
    if ((int) $db->query('SELECT COUNT(*) FROM donation_methods')->fetchColumn() === 0) {
        $stmt = $db->prepare(
            'INSERT INTO donation_methods (country_scope, method_type, label, details, sort_order, active, created_at, updated_at)
             VALUES (:scope, :type, :label, :details, :order, 1, NOW(), NOW())'
        );
        foreach ($defaults['methods'] as [$scope, $type, $label, $details, $order]) {
            $stmt->execute(['scope' => $scope, 'type' => $type, 'label' => $label, 'details' => $details, 'order' => $order]);
        }
        $methodsSeeded = true;
    }

    $presetsSeeded = false;
    if ((int) $db->query('SELECT COUNT(*) FROM donation_preset_amounts')->fetchColumn() === 0) {
        $stmt = $db->prepare(
            'INSERT INTO donation_preset_amounts (currency, amount, sort_order, active, created_at) VALUES (:currency, :amount, :order, 1, NOW())'
        );
        foreach ($defaults['preset_amounts'] as $i => [$currency, $amount]) {
            $stmt->execute(['currency' => $currency, 'amount' => $amount, 'order' => $i]);
        }
        $presetsSeeded = true;
    }

    return ['methods_seeded' => $methodsSeeded, 'presets_seeded' => $presetsSeeded];
}
