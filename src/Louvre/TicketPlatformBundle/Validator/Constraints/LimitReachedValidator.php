<?php

namespace Louvre\TicketPlatformBundle\Validator\Constraints;

use Louvre\TicketPlatformBundle\Model\FormModelStep1;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;



class LimitReachedValidator extends ConstraintValidator
{
    private $manager;

    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function validate($value, Constraint $constraint)
    {
        /**
         * @var FormModelStep1 $value
         */
        $date = $value->getFormatedVisitDate();
        $nbTicketsWished = $value->getNumberOfTickets();

        $ticketRepo = $this->manager->getRepository('LouvreTicketPlatformBundle:Ticket');
        $nbTicketsSold = $ticketRepo->ticketsCount($date);
        dump($nbTicketsSold);

        if (($nbTicketsSold + $nbTicketsWished) <= 3 ) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

}