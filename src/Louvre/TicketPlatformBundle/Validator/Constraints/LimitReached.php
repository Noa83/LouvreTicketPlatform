<?php

namespace Louvre\TicketPlatformBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class LimitReached extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public $message = 'Tous les billets disponibles pour ce jour ont déja été vendus, veuillez choisir une autre date';
}