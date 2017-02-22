<?php

namespace Louvre\TicketPlatformBundle\Services;

use Stripe\Stripe;
use Stripe\Charge;
use Symfony\Component\Debug\ErrorHandler;


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
        //ErrorHandler::register();
        //try {
            $chargeOptions = [
                'amount' => $chargeAmount,
                'currency' => $chargeCurrency,
                'source' => $token,
            ];
        //}catch(\Stripe\Error\Card $e) {
//            $handler = new ErrorHandler();
//            $handler->handle( $e );
//            $errorMessage = 'Erreur de carte';
//            return $this->render('LouvreTicketPlatformBundle:Ticket:Step3.html.twig', [
//                'recap1' => $recapTickets1,
//                'recap2' => $recapTickets2,
//                'errorMessage' => $errorMessage
//            ]);
//        }
        return Charge::create($chargeOptions);

    }

}