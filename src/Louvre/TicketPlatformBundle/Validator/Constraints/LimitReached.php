<?php

namespace Louvre\TicketPlatformBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class LimitReached extends Constraint
{
    public $message = 'Tous les billets disponibles pour ce jour ont déja été vendus, veuillez choisir une autre date';
}