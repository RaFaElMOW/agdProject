<?php

declare(strict_types=1);

/**
 * Guarded HTTP migration runner — exists because the target shared host has no SSH.
 * Reused for every future phase: it scans database/migrations/*.sql fresh on each call
 * and only executes what isn't in `migrations_log` yet, so adding new migration files
 * later needs no changes here.
 *
 * Usage: POST only — the token is read from the request body, so it never lands in the
 * Apache access log or browser history the way a query string would.
 *   POST /tools/migrate.php   (body: token=..., optionally seed=1)
 *
 * Security model:
 *  - POST only; GET (and any other method) is rejected before the token is even checked.
 *  - A signed token (MIGRATE_TOKEN in .env) is always required, compared with hash_equals.
 *  - Rate-limited per IP — this is a bootstrap/maintenance tool, not a high-traffic
 *    endpoint, so a handful of requests per minute is already generous.
 *  - Once at least one user exists in the database, the token alone is no longer enough:
 *    the caller must also be an authenticated session with the `users.manage` permission.
 *    This closes the bootstrap-vs-ongoing gap (no admin exists yet on the very first run),
 *    and means that for every run AFTER the first, this endpoint only ever returns
 *    migration/role names — never a secret — even if the token were somehow guessed.
 *  - The one genuinely sensitive value this can ever return is the auto-generated first
 *    admin password, and only on the very first successful seed (before any user exists).
 *    Set ADMIN_PASSWORD in .env before that first run to avoid generating/transmitting one
 *    at all — see the deploy runbook.
 *  - After the initial rollout, set MIGRATE_TOKEN to empty (or delete this file) per the
 *    production checklist in the Phase 4 deploy runbook; regenerate it before using again.
 */

require __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;
use App\Core\MigrationRunner;
use App\Core\Request;
use App\Repositories\UserRepository;
use App\Services\AuditService;
use App\Services\RateLimiterService;

header('Content-Type: application/json; charset=utf-8');

$request = Request::capture();

if ($request->method() !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['ok' => false, 'error' => 'Use POST.']);
    exit;
}

if (!(new RateLimiterService())->attempt('tools-migrate:' . $request->ip(), 10, 60)) {
    http_response_code(429);
    echo json_encode(['ok' => false, 'error' => 'Muitas requisições. Aguarde um momento.']);
    exit;
}

$configuredToken = (string) config('app.migrate_token', '');
$providedToken = (string) ($_POST['token'] ?? '');

if ($configuredToken === '' || !hash_equals($configuredToken, $providedToken)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Token inválido ou não configurado.']);
    exit;
}

try {
    $hasUsers = (int) Database::connection()->query('SELECT COUNT(*) FROM users')->fetchColumn() > 0;
} catch (\Throwable) {
    $hasUsers = false; // users table doesn't exist yet -> first bootstrap run
}

if ($hasUsers) {
    $userId = $_SESSION['user_id'] ?? null;
    $tokenVersion = $_SESSION['token_version'] ?? null;
    $users = new UserRepository();
    $user = $userId !== null ? $users->findById((int) $userId) : null;

    $authorized = $user !== null
        && $user['status'] === 'active'
        && (int) $user['token_version'] === (int) $tokenVersion
        && in_array('users.manage', $users->permissionSlugsFor((int) $user['id']), true);

    if (!$authorized) {
        http_response_code(403);
        echo json_encode(['ok' => false, 'error' => 'Já existem usuários cadastrados: autentique-se no painel como administrador antes de rodar migrations.']);
        exit;
    }
}

$migrationResult = (new MigrationRunner())->run();
$result = [
    'ok' => true,
    'migrations_executed' => $migrationResult['executed'],
];

if ($migrationResult['already_applied_manually'] !== []) {
    $result['note'] = 'Algumas migrations já existiam no banco (provavelmente importadas manualmente via phpMyAdmin) e foram apenas marcadas como aplicadas, sem erro.';
    $result['already_applied_manually'] = $migrationResult['already_applied_manually'];
}

if (($_POST['seed'] ?? '') === '1') {
    require __DIR__ . '/../database/seeds/seed.php';
    $result['seed'] = [
        'roles_permissions' => seed_roles_and_permissions(),
        'admin' => seed_first_admin(),
        'settings_menus_content' => seed_settings_menus_and_content(),
        'donation_methods' => seed_donation_methods(),
    ];
}

(new AuditService())->log($hasUsers ? (int) ($_SESSION['user_id'] ?? 0) : null, 'migrate_run', $request->ip(), null, null, $result['migrations_executed']);

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
