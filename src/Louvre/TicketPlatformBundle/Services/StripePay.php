<?php

namespace Louvre\TicketPlatformBundle\Services;

use Stripe\Stripe;
use Stripe\Charge;


class StripePay extends Stripe
{
    public function __construct($stripeApiKey)
    {
        self::setApiKey($stripeApiKey);

        return $this;
    }

    /**
     * @param int $chargeAmount: The charge amount in cents
     * @param string $chargeCurrency: The charge currency to use
     * @param string $token: The payment token returned by the payment form (Stripe.js)
     * @return Charge
     */
    public function createCharge($chargeAmount, $chargeCurrency, $token)
    {
            $chargeOptions = [
                'amount' => $chargeAmount,
                'currency' => $chargeCurrency,
                'source' => $token,
            ];

        return Charge::create($chargeOptions);
    }

}