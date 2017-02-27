<?php

namespace Louvre\TicketPlatformBundle\Services;

use Doctrine\ORM\Mapping as ORM;
use Louvre\TicketPlatformBundle\Entity\Ticket;
use Louvre\TicketPlatformBundle\Entity\TicketOrder;

class FormToEntity
{
    private $manager;

    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function bddRecording($recapTickets1, $recapTickets2, $recapPayment)
    {
        $ticketOrder = new TicketOrder();
        $ticketOrder->setBookingCode($recapPayment->getReservationCode());
        $ticketOrder->setEmail($recapTickets1->getEmail());

        foreach ($recapTickets2->getForm2()->toArray() as $form ){
            $ticket = new Ticket();
            $ticket->setName($form->getName());
            $ticket->setFirstName($form->getFirstName());
            $ticket->setVisitDate($recapTickets1->getFormatedVisitDate());
            $ticket->setPrice($form->getRealPrice());
            $ticketOrder->addTicket($ticket);
            $ticket->setTicketOrder($ticketOrder);
        }


        $em = $this->manager;
        $em->persist($ticketOrder);
        $em->flush();
    }
}