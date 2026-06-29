<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\SettingsRepository;
use App\Services\UploadService;
use App\Support\Auth;
use App\Support\Flash;
use App\Support\Paths;
use App\Support\Settings;

class SettingsController
{
    private const TEXT_FIELDS = [
        'site_name' => 'identity',
        'social_facebook' => 'social',
        'social_instagram' => 'social',
        'social_twitter' => 'social',
        'social_youtube' => 'social',
        'social_spotify' => 'social',
        'social_whatsapp' => 'social',
        'contact_address' => 'contact',
        'contact_whatsapp_display' => 'contact',
        'contact_email' => 'contact',
        'contact_phone' => 'contact',
        'seo_meta_title' => 'seo',
        'seo_meta_description' => 'seo',
        'ga_id' => 'analytics',
        'gtm_id' => 'analytics',
        'color_primary' => 'branding',
        'color_secondary' => 'branding',
        'stat_children_served' => 'content',
    ];

    private const IMAGE_FIELDS = [
        'site_logo' => 'identity',
        'site_logo_white' => 'identity',
        'site_favicon' => 'identity',
        'seo_og_image' => 'seo',
    ];

    public function showForm(Request $request): void
    {
        View::output('admin/settings', [
            'settings' => (new SettingsRepository())->all(),
            'success' => Flash::pull('settings_success'),
            'error' => Flash::pull('settings_error'),
        ], 'admin/layout');
    }

    public function submit(Request $request): void
    {
        $repository = new SettingsRepository();

        foreach (self::TEXT_FIELDS as $key => $group) {
            $value = trim((string) $request->input($key, ''));
            if ($key === 'stat_children_served') {
                $value = preg_replace('/[^0-9]/', '', $value) ?: '0';
            }
            $repository->set($key, $value, $group);
        }

        $uploadService = new UploadService(Paths::uploads('branding'));

        foreach (self::IMAGE_FIELDS as $key => $group) {
            if (!empty($_FILES[$key]['name'])) {
                try {
                    $filename = $uploadService->storeImage($_FILES[$key]);
                    $repository->set($key, 'uploads/branding/' . $filename, $group);
                } catch (\RuntimeException $e) {
                    Flash::set('settings_error', $e->getMessage());
                    Response::redirect('/admin/configuracoes');
                }
            }
        }

        Settings::refresh();

        (new \App\Services\AuditService())->log(Auth::id(), 'settings_updated', $request->ip());

        Flash::set('settings_success', 'Configurações salvas com sucesso.');
        Response::redirect('/admin/configuracoes');
    }
}
