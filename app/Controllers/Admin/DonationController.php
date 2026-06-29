<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\DonationMethodRepository;
use App\Repositories\DonationPresetAmountRepository;
use App\Repositories\PaypalAccountRepository;
use App\Services\AuditService;
use App\Support\Auth;
use App\Support\Flash;

class DonationController
{
    // --- Métodos de doação (bancos, PIX, etc.) ---

    public function methodsIndex(Request $request): void
    {
        View::output('admin/donation-methods', [
            'methods' => (new DonationMethodRepository())->all(),
            'success' => Flash::pull('donation_success'),
            'error' => Flash::pull('donation_error'),
        ], 'admin/layout');
    }

    public function methodCreate(Request $request): void
    {
        View::output('admin/donation-method-form', ['method' => null], 'admin/layout');
    }

    public function methodStore(Request $request): void
    {
        $this->saveMethod($request, null);
    }

    public function methodEdit(Request $request, array $params): void
    {
        $method = (new DonationMethodRepository())->find((int) $params['id']);
        if ($method === null) {
            Response::notFound('Método não encontrado.');
        }
        View::output('admin/donation-method-form', ['method' => $method], 'admin/layout');
    }

    public function methodUpdate(Request $request, array $params): void
    {
        $this->saveMethod($request, (int) $params['id']);
    }

    public function methodDestroy(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new DonationMethodRepository())->delete($id);
        (new AuditService())->log(Auth::id(), 'donation_method_deleted', $request->ip(), 'donation_method', (string) $id);
        Flash::set('donation_success', 'Método removido.');
        Response::redirect('/admin/doacoes');
    }

    private function saveMethod(Request $request, ?int $id): void
    {
        $repository = new DonationMethodRepository();
        $data = [
            'country_scope' => $request->input('country_scope', 'national'),
            'method_type' => $request->input('method_type', 'bank'),
            'label' => trim((string) $request->input('label', '')),
            'details' => trim((string) $request->input('details', '')),
            'sort_order' => (int) $request->input('sort_order', 0),
            'active' => $request->input('active') ? 1 : 0,
        ];

        if ($id === null) {
            $newId = $repository->create($data);
            (new AuditService())->log(Auth::id(), 'donation_method_created', $request->ip(), 'donation_method', (string) $newId);
        } else {
            $repository->update($id, $data);
            (new AuditService())->log(Auth::id(), 'donation_method_updated', $request->ip(), 'donation_method', (string) $id);
        }

        Flash::set('donation_success', 'Método salvo com sucesso.');
        Response::redirect('/admin/doacoes');
    }

    // --- Contas PayPal ---

    public function paypalIndex(Request $request): void
    {
        View::output('admin/donation-paypal', [
            'accounts' => (new PaypalAccountRepository())->all(),
            'success' => Flash::pull('paypal_success'),
            'error' => Flash::pull('paypal_error'),
        ], 'admin/layout');
    }

    public function paypalCreate(Request $request): void
    {
        View::output('admin/donation-paypal-form', ['account' => null], 'admin/layout');
    }

    public function paypalStore(Request $request): void
    {
        $this->savePaypal($request, null);
    }

    public function paypalEdit(Request $request, array $params): void
    {
        $account = (new PaypalAccountRepository())->find((int) $params['id']);
        if ($account === null) {
            Response::notFound('Conta não encontrada.');
        }
        View::output('admin/donation-paypal-form', ['account' => $account], 'admin/layout');
    }

    public function paypalUpdate(Request $request, array $params): void
    {
        $this->savePaypal($request, (int) $params['id']);
    }

    public function paypalDestroy(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new PaypalAccountRepository())->delete($id);
        (new AuditService())->log(Auth::id(), 'paypal_account_deleted', $request->ip(), 'paypal_account', (string) $id);
        Flash::set('paypal_success', 'Conta removida.');
        Response::redirect('/admin/doacoes/paypal');
    }

    private function savePaypal(Request $request, ?int $id): void
    {
        $businessId = trim((string) $request->input('paypal_business_id', ''));
        if ($businessId === '') {
            Flash::set('paypal_error', 'Informe o e-mail ou ID comercial PayPal.');
            Response::redirect($id !== null ? '/admin/doacoes/paypal/' . $id . '/editar' : '/admin/doacoes/paypal/novo');
        }

        $repository = new PaypalAccountRepository();
        $data = [
            'label' => trim((string) $request->input('label', '')),
            'currency' => trim((string) $request->input('currency', 'USD')),
            'paypal_business_id' => $businessId,
            'country_scope' => $request->input('country_scope', 'international'),
            'sort_order' => (int) $request->input('sort_order', 0),
            'active' => $request->input('active') ? 1 : 0,
        ];

        if ($id === null) {
            $newId = $repository->create($data);
            (new AuditService())->log(Auth::id(), 'paypal_account_created', $request->ip(), 'paypal_account', (string) $newId);
        } else {
            $repository->update($id, $data);
            (new AuditService())->log(Auth::id(), 'paypal_account_updated', $request->ip(), 'paypal_account', (string) $id);
        }

        Flash::set('paypal_success', 'Conta salva com sucesso.');
        Response::redirect('/admin/doacoes/paypal');
    }

    // --- Valores predefinidos ---

    public function presetsIndex(Request $request): void
    {
        View::output('admin/donation-presets', [
            'presets' => (new DonationPresetAmountRepository())->all(),
            'success' => Flash::pull('preset_success'),
            'error' => Flash::pull('preset_error'),
        ], 'admin/layout');
    }

    public function presetStore(Request $request): void
    {
        $currency = trim((string) $request->input('currency', ''));
        $amount = trim((string) $request->input('amount', ''));

        if ($currency === '' || !is_numeric($amount) || (float) $amount <= 0) {
            Flash::set('preset_error', 'Informe moeda e valor positivo válidos.');
            Response::redirect('/admin/doacoes/valores');
        }

        $repository = new DonationPresetAmountRepository();
        $id = $repository->create([
            'currency' => strtoupper($currency),
            'amount' => $amount,
            'sort_order' => (int) $request->input('sort_order', 0),
            'active' => 1,
        ]);
        (new AuditService())->log(Auth::id(), 'donation_preset_created', $request->ip(), 'donation_preset_amount', (string) $id);

        Flash::set('preset_success', 'Valor adicionado.');
        Response::redirect('/admin/doacoes/valores');
    }

    public function presetDestroy(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        (new DonationPresetAmountRepository())->delete($id);
        (new AuditService())->log(Auth::id(), 'donation_preset_deleted', $request->ip(), 'donation_preset_amount', (string) $id);
        Flash::set('preset_success', 'Valor removido.');
        Response::redirect('/admin/doacoes/valores');
    }
}
