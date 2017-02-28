<?php

namespace Louvre\TicketPlatformBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class MaxHourExceeded extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public $message = 'Il n\'est plus possible de sélectionner un billet de type journée pour aujourd\'hui, veuillez 
    sélectionner un billet demie-journée ou choisir une autre date';
}