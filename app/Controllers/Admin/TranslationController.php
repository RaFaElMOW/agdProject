<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\TranslationRepository;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;

class TranslationController
{
    private function langConfig(): array
    {
        return require __DIR__ . '/../../../lang/config.php';
    }

    public function index(Request $request): void
    {
        $config = $this->langConfig();
        $langCode = (string) $request->input('lang', $config['default']);
        if (!isset($config['languages'][$langCode])) {
            $langCode = $config['default'];
        }

        $defaults = require $config['languages'][$langCode]['file'];
        $overrides = (new TranslationRepository())->overridesFor($langCode);

        $rows = [];
        foreach ($defaults as $key => $defaultValue) {
            $hasOverride = isset($overrides[$key]) && $overrides[$key] !== '';
            $rows[] = [
                'key' => $key,
                'default' => $defaultValue,
                'current' => $hasOverride ? $overrides[$key] : $defaultValue,
                'overridden' => $hasOverride,
            ];
        }

        View::output('admin/translations', [
            'languages' => $config['languages'],
            'currentLang' => $langCode,
            'rows' => $rows,
            'success' => Flash::pull('translation_success'),
        ], 'admin/layout');
    }

    public function update(Request $request): void
    {
        $config = $this->langConfig();
        $langCode = (string) $request->input('lang', '');

        if (!isset($config['languages'][$langCode])) {
            Flash::set('translation_error', 'Idioma inválido.');
            Response::redirect('/admin/traducoes');
        }

        $defaults = require $config['languages'][$langCode]['file'];
        $repository = new TranslationRepository();
        $values = (array) ($_POST['values'] ?? []);

        $changed = 0;
        foreach ($defaults as $key => $defaultValue) {
            if (!array_key_exists($key, $values)) {
                continue;
            }

            $submitted = trim((string) $values[$key]);
            if ($submitted === '' || $submitted === trim((string) $defaultValue)) {
                $repository->clearOverride($langCode, $key);
            } else {
                $repository->setOverride($langCode, $key, $submitted);
                $changed++;
            }
        }

        (new AuditService())->log(Auth::id(), 'translations_updated', $request->ip(), 'translations', $langCode, ['changed_keys' => $changed]);

        Flash::set('translation_success', "Traduções salvas ({$changed} personalizadas).");
        Response::redirect('/admin/traducoes?lang=' . $langCode);
    }
}
