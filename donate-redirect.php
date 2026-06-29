<?php

/**
 * Pure redirect — no PayPal API call happens here. Validates the account/amount
 * server-side then 302s to PayPal's own donate URL, exactly like a static link would.
 */

require_once __DIR__ . '/includes/cms-bootstrap.php';

use App\Repositories\PaypalAccountRepository;
use App\Services\DonationService;

$accountId = (int) ($_GET['account_id'] ?? 0);
$amount = trim((string) ($_GET['amount'] ?? ''));

$service = new DonationService();
$account = $accountId > 0 ? (new PaypalAccountRepository())->find($accountId) : null;

if ($account === null || !$account['active'] || !$service->isValidAmount($amount)) {
    header('Location: donate.php?donation=invalid#online');
    exit;
}

header('Location: ' . $service->buildPaypalUrl($account, $amount));
exit;
