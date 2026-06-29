<?php

namespace App\Services;

use App\Repositories\DonationPresetAmountRepository;
use App\Repositories\PaypalAccountRepository;

/**
 * No direct PayPal API integration (by design — see architecture notes): this only ever
 * builds a plain PayPal "Donate" redirect URL. PayPal business id/email is never secret,
 * so exposing it in the URL is the intended, supported integration method.
 */
class DonationService
{
    public function presetsFor(string $currency): array
    {
        return (new DonationPresetAmountRepository())->activeByCurrency($currency);
    }

    public function primaryAccountFor(string $scope): ?array
    {
        return (new PaypalAccountRepository())->activeFindFirstByScope($scope);
    }

    public function buildPaypalUrl(array $account, string $amount): string
    {
        $params = [
            'cmd' => '_donations',
            'business' => $account['paypal_business_id'],
            'currency_code' => $account['currency'],
            'amount' => $amount,
            'item_name' => 'Doação - AGD Niger',
        ];

        return 'https://www.paypal.com/cgi-bin/webscr?' . http_build_query($params);
    }

    public function isValidAmount(string $amount): bool
    {
        return is_numeric($amount) && (float) $amount > 0 && (float) $amount <= 1000000;
    }
}
