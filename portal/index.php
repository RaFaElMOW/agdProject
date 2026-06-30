<?php

declare(strict_types=1);

require __DIR__ . '/../app/bootstrap.php';

use App\Controllers\Admin\AuditController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\ChangePasswordController;
use App\Controllers\Admin\ContactMessageController;
use App\Controllers\Admin\ContentController;
use App\Controllers\Admin\DonationController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\MediaController;
use App\Controllers\Admin\MenuController;
use App\Controllers\Admin\BlogCommentController;
use App\Controllers\Admin\BlogController;
use App\Controllers\Admin\BookController;
use App\Controllers\Admin\ProjectController;
use App\Controllers\Admin\SecurityController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\SponsorshipController;
use App\Controllers\Admin\TranslationController;
use App\Controllers\Admin\TeamController;
use App\Controllers\Admin\TestimonialController;
use App\Controllers\Admin\UserController;
use App\Core\Request;
use App\Core\Router;
use App\Middleware\CsrfMiddleware;
use App\Middleware\RateLimitMiddleware;
use App\Middleware\RbacMiddleware;
use App\Middleware\SecurityHeadersMiddleware;
use App\Middleware\SessionAuthMiddleware;
use App\Security\AdminPath;
use App\Security\SecuritySettings;
use App\Support\BasePath;

// portal/index.php lives one level below the project root, just like admin/index.php did,
// so the same BasePath derivation applies regardless of domain root (cPanel) or subfolder
// (local XAMPP /agdProject).
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$projectRoot = rtrim(dirname($scriptDir), '/');
BasePath::set($projectRoot === '' ? '' : $projectRoot);

// ---------------------------------------------------------------------------
// Token-gated entry point. Everything below this point only runs if the
// request carries a valid admin_route_token segment; otherwise we 404
// without revealing that an admin panel exists.
// ---------------------------------------------------------------------------
$requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
AdminPath::setOriginalUri($requestUri);

$basePath = BasePath::get();
$pathOnly = strtok($requestUri, '?') ?: '/';
if ($basePath !== '' && str_starts_with($pathOnly, $basePath)) {
    $pathOnly = substr($pathOnly, strlen($basePath));
}

if (!preg_match('#^/portal/([^/]+)((?:/.*)?)$#', $pathOnly, $matches)) {
    http_response_code(404);
    echo 'Não encontrado.';
    exit;
}

$candidateToken = $matches[1];
$remainder = $matches[2] === '' ? '/' : $matches[2];

$securitySettings = SecuritySettings::getInstance();
$storedToken = $securitySettings->get('admin_route_token');

if ($storedToken === '' || strlen($storedToken) < 48 || !hash_equals($storedToken, $candidateToken)) {
    http_response_code(404);
    echo 'Não encontrado.';
    exit;
}

AdminPath::setPrefix('/portal/' . $storedToken);

// Rewrite REQUEST_URI so the existing route table (registered as /admin/...)
// keeps working unchanged. Query string is preserved.
$queryString = $_SERVER['QUERY_STRING'] ?? '';
$_SERVER['REQUEST_URI'] = $basePath . '/admin' . $remainder . ($queryString !== '' ? '?' . $queryString : '');

$request = Request::capture(BasePath::get());

$security = new SecurityHeadersMiddleware();
$csrf = new CsrfMiddleware();
$auth = new SessionAuthMiddleware();
$loginThrottle = new RateLimitMiddleware('admin-login', 10, 60);
$rbac = fn (string $permission) => new RbacMiddleware($permission);

$router = new Router();

$authController = new AuthController();
$dashboardController = new DashboardController();
$changePasswordController = new ChangePasswordController();
$settingsController = new SettingsController();
$securityController = new SecurityController();
$menuController = new MenuController();
$contentController = new ContentController();
$userController = new UserController();
$auditController = new AuditController();
$teamController = new TeamController();
$testimonialController = new TestimonialController();
$projectController = new ProjectController();
$sponsorshipController = new SponsorshipController();
$bookController = new BookController();
$mediaController = new MediaController();
$blogController = new BlogController();
$blogCommentController = new BlogCommentController();
$contactMessageController = new ContactMessageController();
$donationController = new DonationController();
$translationController = new TranslationController();

$router->get('/admin/login', [$authController, 'showLogin'], [$security]);
$router->post('/admin/login', [$authController, 'login'], [$security, $loginThrottle, $csrf]);
$router->post('/admin/logout', [$authController, 'logout'], [$security, $auth, $csrf]);
$router->post('/admin/logout-global', [$authController, 'logoutGlobal'], [$security, $auth, $csrf]);

$router->get('/admin/trocar-senha', [$changePasswordController, 'showForm'], [$security, $auth]);
$router->post('/admin/trocar-senha', [$changePasswordController, 'submit'], [$security, $auth, $csrf]);

$router->get('/admin', [$dashboardController, 'index'], [$security, $auth]);

// Configurações globais + menus (header/footer)
$router->get('/admin/configuracoes', [$settingsController, 'showForm'], [$security, $auth, $rbac('settings.manage')]);
$router->post('/admin/configuracoes', [$settingsController, 'submit'], [$security, $auth, $rbac('settings.manage'), $csrf]);

// Segurança (Security Settings)
$router->get('/admin/seguranca', [$securityController, 'showForm'], [$security, $auth, $rbac('security.manage')]);
$router->post('/admin/seguranca', [$securityController, 'submit'], [$security, $auth, $rbac('security.manage'), $csrf]);
$router->post('/admin/seguranca/regenerar-token', [$securityController, 'regenerateToken'], [$security, $auth, $rbac('security.manage'), $csrf]);

$router->get('/admin/menus', [$menuController, 'index'], [$security, $auth, $rbac('settings.manage')]);
$router->get('/admin/menus/novo', [$menuController, 'create'], [$security, $auth, $rbac('settings.manage')]);
$router->post('/admin/menus', [$menuController, 'store'], [$security, $auth, $rbac('settings.manage'), $csrf]);
$router->get('/admin/menus/{id}/editar', [$menuController, 'edit'], [$security, $auth, $rbac('settings.manage')]);
$router->post('/admin/menus/{id}', [$menuController, 'update'], [$security, $auth, $rbac('settings.manage'), $csrf]);
$router->post('/admin/menus/{id}/excluir', [$menuController, 'destroy'], [$security, $auth, $rbac('settings.manage'), $csrf]);

// Conteúdo institucional (About / Quem Somos)
$router->get('/admin/conteudo/sobre', [$contentController, 'showAbout'], [$security, $auth, $rbac('content.manage')]);
$router->post('/admin/conteudo/sobre', [$contentController, 'submitAbout'], [$security, $auth, $rbac('content.manage'), $csrf]);
$router->get('/admin/conteudo/quemsomos', [$contentController, 'showQuemSomos'], [$security, $auth, $rbac('content.manage')]);
$router->post('/admin/conteudo/quemsomos', [$contentController, 'submitQuemSomos'], [$security, $auth, $rbac('content.manage'), $csrf]);

// Usuários
$router->get('/admin/usuarios', [$userController, 'index'], [$security, $auth, $rbac('users.manage')]);
$router->get('/admin/usuarios/novo', [$userController, 'create'], [$security, $auth, $rbac('users.manage')]);
$router->post('/admin/usuarios', [$userController, 'store'], [$security, $auth, $rbac('users.manage'), $csrf]);
$router->get('/admin/usuarios/{id}/editar', [$userController, 'edit'], [$security, $auth, $rbac('users.manage')]);
$router->post('/admin/usuarios/{id}', [$userController, 'update'], [$security, $auth, $rbac('users.manage'), $csrf]);
$router->post('/admin/usuarios/{id}/status', [$userController, 'setStatus'], [$security, $auth, $rbac('users.manage'), $csrf]);
$router->post('/admin/usuarios/{id}/resetar-senha', [$userController, 'resetPassword'], [$security, $auth, $rbac('users.manage'), $csrf]);

// Auditoria
$router->get('/admin/auditoria', [$auditController, 'index'], [$security, $auth, $rbac('audit.view')]);

// Equipe
$router->get('/admin/equipe', [$teamController, 'index'], [$security, $auth, $rbac('team.manage')]);
$router->get('/admin/equipe/novo', [$teamController, 'create'], [$security, $auth, $rbac('team.manage')]);
$router->post('/admin/equipe', [$teamController, 'store'], [$security, $auth, $rbac('team.manage'), $csrf]);
$router->get('/admin/equipe/{id}/editar', [$teamController, 'edit'], [$security, $auth, $rbac('team.manage')]);
$router->post('/admin/equipe/{id}', [$teamController, 'update'], [$security, $auth, $rbac('team.manage'), $csrf]);
$router->post('/admin/equipe/{id}/excluir', [$teamController, 'destroy'], [$security, $auth, $rbac('team.manage'), $csrf]);

// Depoimentos
$router->get('/admin/depoimentos', [$testimonialController, 'index'], [$security, $auth, $rbac('testimonials.manage')]);
$router->get('/admin/depoimentos/novo', [$testimonialController, 'create'], [$security, $auth, $rbac('testimonials.manage')]);
$router->post('/admin/depoimentos', [$testimonialController, 'store'], [$security, $auth, $rbac('testimonials.manage'), $csrf]);
$router->get('/admin/depoimentos/{id}/editar', [$testimonialController, 'edit'], [$security, $auth, $rbac('testimonials.manage')]);
$router->post('/admin/depoimentos/{id}', [$testimonialController, 'update'], [$security, $auth, $rbac('testimonials.manage'), $csrf]);
$router->post('/admin/depoimentos/{id}/excluir', [$testimonialController, 'destroy'], [$security, $auth, $rbac('testimonials.manage'), $csrf]);

// Projetos
$router->get('/admin/projetos', [$projectController, 'index'], [$security, $auth, $rbac('projects.manage')]);
$router->get('/admin/projetos/novo', [$projectController, 'create'], [$security, $auth, $rbac('projects.manage')]);
$router->post('/admin/projetos', [$projectController, 'store'], [$security, $auth, $rbac('projects.manage'), $csrf]);
$router->get('/admin/projetos/{id}/editar', [$projectController, 'edit'], [$security, $auth, $rbac('projects.manage')]);
$router->post('/admin/projetos/{id}', [$projectController, 'update'], [$security, $auth, $rbac('projects.manage'), $csrf]);
$router->post('/admin/projetos/{id}/excluir', [$projectController, 'destroy'], [$security, $auth, $rbac('projects.manage'), $csrf]);
$router->post('/admin/projetos/{id}/galeria', [$projectController, 'addGalleryImage'], [$security, $auth, $rbac('projects.manage'), $csrf]);
$router->post('/admin/projetos/{id}/galeria/{imageId}/excluir', [$projectController, 'deleteGalleryImage'], [$security, $auth, $rbac('projects.manage'), $csrf]);

// Apadrinhamento
$router->get('/admin/apadrinhamento', [$sponsorshipController, 'index'], [$security, $auth, $rbac('sponsorship.manage')]);
$router->get('/admin/apadrinhamento/novo', [$sponsorshipController, 'create'], [$security, $auth, $rbac('sponsorship.manage')]);
$router->post('/admin/apadrinhamento', [$sponsorshipController, 'store'], [$security, $auth, $rbac('sponsorship.manage'), $csrf]);
$router->get('/admin/apadrinhamento/{id}/editar', [$sponsorshipController, 'edit'], [$security, $auth, $rbac('sponsorship.manage')]);
$router->post('/admin/apadrinhamento/{id}', [$sponsorshipController, 'update'], [$security, $auth, $rbac('sponsorship.manage'), $csrf]);
$router->post('/admin/apadrinhamento/{id}/excluir', [$sponsorshipController, 'destroy'], [$security, $auth, $rbac('sponsorship.manage'), $csrf]);

// Livros
$router->get('/admin/livros', [$bookController, 'index'], [$security, $auth, $rbac('books.manage')]);
$router->get('/admin/livros/novo', [$bookController, 'create'], [$security, $auth, $rbac('books.manage')]);
$router->post('/admin/livros', [$bookController, 'store'], [$security, $auth, $rbac('books.manage'), $csrf]);
$router->get('/admin/livros/{id}/editar', [$bookController, 'edit'], [$security, $auth, $rbac('books.manage')]);
$router->post('/admin/livros/{id}', [$bookController, 'update'], [$security, $auth, $rbac('books.manage'), $csrf]);
$router->post('/admin/livros/{id}/excluir', [$bookController, 'destroy'], [$security, $auth, $rbac('books.manage'), $csrf]);

// Mídia
$router->get('/admin/midia', [$mediaController, 'index'], [$security, $auth, $rbac('media.manage')]);
$router->get('/admin/midia/novo', [$mediaController, 'create'], [$security, $auth, $rbac('media.manage')]);
$router->post('/admin/midia', [$mediaController, 'store'], [$security, $auth, $rbac('media.manage'), $csrf]);
$router->get('/admin/midia/{id}/editar', [$mediaController, 'edit'], [$security, $auth, $rbac('media.manage')]);
$router->post('/admin/midia/{id}', [$mediaController, 'update'], [$security, $auth, $rbac('media.manage'), $csrf]);
$router->post('/admin/midia/{id}/excluir', [$mediaController, 'destroy'], [$security, $auth, $rbac('media.manage'), $csrf]);

// Blog — literal/specific routes MUST be registered before the generic /{id} routes below,
// since the router matches in registration order and {id} would otherwise swallow paths
// like "categorias" or "comentarios" as if they were a post id.
$router->get('/admin/blog', [$blogController, 'index'], [$security, $auth, $rbac('blog.manage')]);
$router->get('/admin/blog/novo', [$blogController, 'create'], [$security, $auth, $rbac('blog.manage')]);
$router->post('/admin/blog', [$blogController, 'store'], [$security, $auth, $rbac('blog.manage'), $csrf]);

$router->get('/admin/blog/categorias', [$blogController, 'categories'], [$security, $auth, $rbac('blog.manage')]);
$router->post('/admin/blog/categorias', [$blogController, 'storeCategory'], [$security, $auth, $rbac('blog.manage'), $csrf]);
$router->post('/admin/blog/categorias/{id}/excluir', [$blogController, 'destroyCategory'], [$security, $auth, $rbac('blog.manage'), $csrf]);

$router->get('/admin/blog/comentarios', [$blogCommentController, 'index'], [$security, $auth, $rbac('comments.moderate')]);
$router->post('/admin/blog/comentarios/{id}/aprovar', [$blogCommentController, 'approve'], [$security, $auth, $rbac('comments.moderate'), $csrf]);
$router->post('/admin/blog/comentarios/{id}/spam', [$blogCommentController, 'markSpam'], [$security, $auth, $rbac('comments.moderate'), $csrf]);
$router->post('/admin/blog/comentarios/{id}/excluir', [$blogCommentController, 'destroy'], [$security, $auth, $rbac('comments.moderate'), $csrf]);

$router->get('/admin/blog/{id}/editar', [$blogController, 'edit'], [$security, $auth, $rbac('blog.manage')]);
$router->post('/admin/blog/{id}', [$blogController, 'update'], [$security, $auth, $rbac('blog.manage'), $csrf]);
$router->post('/admin/blog/{id}/excluir', [$blogController, 'destroy'], [$security, $auth, $rbac('blog.manage'), $csrf]);

// Mensagens de contato
$router->get('/admin/mensagens', [$contactMessageController, 'index'], [$security, $auth, $rbac('contact.view')]);
$router->post('/admin/mensagens/{id}/arquivar', [$contactMessageController, 'archive'], [$security, $auth, $rbac('contact.view'), $csrf]);
$router->post('/admin/mensagens/{id}/excluir', [$contactMessageController, 'destroy'], [$security, $auth, $rbac('contact.view'), $csrf]);

// Doações — literal/specific routes before the generic /{id} routes (same reasoning as blog).
$router->get('/admin/doacoes', [$donationController, 'methodsIndex'], [$security, $auth, $rbac('donations.manage')]);
$router->get('/admin/doacoes/novo', [$donationController, 'methodCreate'], [$security, $auth, $rbac('donations.manage')]);
$router->post('/admin/doacoes', [$donationController, 'methodStore'], [$security, $auth, $rbac('donations.manage'), $csrf]);

$router->get('/admin/doacoes/paypal', [$donationController, 'paypalIndex'], [$security, $auth, $rbac('donations.manage')]);
$router->get('/admin/doacoes/paypal/novo', [$donationController, 'paypalCreate'], [$security, $auth, $rbac('donations.manage')]);
$router->post('/admin/doacoes/paypal', [$donationController, 'paypalStore'], [$security, $auth, $rbac('donations.manage'), $csrf]);
$router->get('/admin/doacoes/paypal/{id}/editar', [$donationController, 'paypalEdit'], [$security, $auth, $rbac('donations.manage')]);
$router->post('/admin/doacoes/paypal/{id}', [$donationController, 'paypalUpdate'], [$security, $auth, $rbac('donations.manage'), $csrf]);
$router->post('/admin/doacoes/paypal/{id}/excluir', [$donationController, 'paypalDestroy'], [$security, $auth, $rbac('donations.manage'), $csrf]);

$router->get('/admin/doacoes/valores', [$donationController, 'presetsIndex'], [$security, $auth, $rbac('donations.manage')]);
$router->post('/admin/doacoes/valores', [$donationController, 'presetStore'], [$security, $auth, $rbac('donations.manage'), $csrf]);
$router->post('/admin/doacoes/valores/{id}/excluir', [$donationController, 'presetDestroy'], [$security, $auth, $rbac('donations.manage'), $csrf]);

$router->get('/admin/doacoes/{id}/editar', [$donationController, 'methodEdit'], [$security, $auth, $rbac('donations.manage')]);
$router->post('/admin/doacoes/{id}', [$donationController, 'methodUpdate'], [$security, $auth, $rbac('donations.manage'), $csrf]);
$router->post('/admin/doacoes/{id}/excluir', [$donationController, 'methodDestroy'], [$security, $auth, $rbac('donations.manage'), $csrf]);

// Traduções
$router->get('/admin/traducoes', [$translationController, 'index'], [$security, $auth, $rbac('translations.manage')]);
$router->post('/admin/traducoes', [$translationController, 'update'], [$security, $auth, $rbac('translations.manage'), $csrf]);

$router->dispatch($request);
