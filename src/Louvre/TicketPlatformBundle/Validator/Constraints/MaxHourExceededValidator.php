<?php

namespace Louvre\TicketPlatformBundle\Validator\Constraints;

use Louvre\TicketPlatformBundle\Model\FormModelStep1;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class MaxHourExceededValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value->getTicketType() == 'Billet Journée') {
            /**
             * @var FormModelStep1 $value
             */
            $visitDate = $value->getFormatedVisitDate();
            $currentDate = new \DateTime();
            $currentDate = $currentDate->format('Ymd');
            $visitDate = $visitDate->format('Ymd');

            if ($visitDate == $currentDate) {
                $currentTime = new \DateTime();
                $currentTime = $currentTime->format('H:i:s');

                $currentDateTime = \DateTime::createFromFormat('Ymd H:i:s', $currentDate . ' ' . $currentTime);
                $maxDateTime = \DateTime::createFromFormat('Ymd H:i:s', $currentDate . ' 14:00:00');

                if ($currentDateTime > $maxDateTime) {
                    $this->context->buildViolation($constraint->message)
                        ->atPath('ticketType')
                        ->addViolation();
                }
            }
        }

    }
}