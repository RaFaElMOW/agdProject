<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\SiteContentRepository;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;

class ContentController
{
    private const ABOUT_FIELDS = [
        'hero_quote' => 'About Hero Quote',
        'welcome_heading' => 'Welcome to Welfare Stablished Since 1898',
        'intro_text' => 'About Intro Text',
        'areas_heading' => 'About Areas Heading',
        'areas_text' => 'About Areas Text',
        'mission_heading' => 'About Mission Heading',
        'mission_text' => 'About Mission Text',
        'vision_heading' => 'About Vision Heading',
        'vision_text' => 'About Vision Text',
        'vision_prayer_text' => 'About Vision Prayer Text',
        'values_heading' => 'About Values Heading',
        'values_text' => 'About Values Text',
    ];

    private const QUEMSOMOS_FIELDS = [
        'bio_paragraph_1' => 'Alexandre Bio Text 1',
        'bio_paragraph_2' => 'Alexandre Bio Text 2',
        'bio_paragraph_3' => 'Alexandre Bio Text 3',
    ];

    public function showAbout(Request $request): void
    {
        $this->show('about', self::ABOUT_FIELDS, 'admin/content-about');
    }

    public function submitAbout(Request $request): void
    {
        $this->submit($request, 'about', array_keys(self::ABOUT_FIELDS), '/admin/conteudo/sobre');
    }

    public function showQuemSomos(Request $request): void
    {
        $this->show('quemsomos', self::QUEMSOMOS_FIELDS, 'admin/content-quemsomos');
    }

    public function submitQuemSomos(Request $request): void
    {
        $this->submit($request, 'quemsomos', array_keys(self::QUEMSOMOS_FIELDS), '/admin/conteudo/quemsomos');
    }

    private function show(string $key, array $fields, string $view): void
    {
        require_once __DIR__ . '/../../../includes/i18n.php';

        $repository = new SiteContentRepository();
        $stored = $repository->get($key) ?? [];

        $values = [];
        foreach ($fields as $field => $translationKey) {
            $values[$field] = $stored[$field] ?? t($translationKey);
        }

        View::output($view, [
            'values' => $values,
            'success' => Flash::pull('content_success'),
        ], 'admin/layout');
    }

    private function submit(Request $request, string $key, array $fields, string $redirectTo): void
    {
        $repository = new SiteContentRepository();
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = trim((string) $request->input($field, ''));
        }

        $repository->set($key, $data);
        (new AuditService())->log(Auth::id(), 'content_updated', $request->ip(), 'site_content', $key);

        Flash::set('content_success', 'Conteúdo salvo com sucesso.');
        Response::redirect($redirectTo);
    }
}
