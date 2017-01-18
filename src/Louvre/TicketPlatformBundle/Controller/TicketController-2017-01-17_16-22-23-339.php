<?php

namespace Louvre\TicketPlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class TicketController
{
    public function Step1Action()
    {
        return new Response("page 1 - choix / accueil du site");
    }

    public function Step2Action()
    {
        return new Response("page 2 - saisie infos benefs");
    }

    public function Step3Action()
    {
        return new Response("page 3 - recap validation");
    }

    public function  Step4Action()
    {
        return new Response("page 4 - Paiement Stripe");
    }

    public function Step5Action()
    {
        return new Response("page 5 - Confirmation envoi");
    }

    public function Step6Action()
    {
        return new Response("page 6 - Retour au site du musée");
    }
}