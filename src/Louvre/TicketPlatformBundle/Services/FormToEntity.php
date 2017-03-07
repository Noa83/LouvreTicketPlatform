<?php

namespace Louvre\TicketPlatformBundle\Services;

use Louvre\TicketPlatformBundle\Entity\Ticket;
use Louvre\TicketPlatformBundle\Entity\TicketOrder;
use Louvre\TicketPlatformBundle\Model\FormModelStep1;
use Louvre\TicketPlatformBundle\Model\FormModelStep2;
use Louvre\TicketPlatformBundle\Model\PaymentModel;

class FormToEntity
{
    private $manager;

    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function bddRecording(FormModelStep1 $recapTickets1, FormModelStep2 $recapTickets2, PaymentModel $recapPayment)
    {
        $ticketOrder = new TicketOrder();
        $ticketOrder->setBookingCode($recapPayment->getReservationCode());
        $ticketOrder->setEmail($recapTickets1->getEmail());

        foreach ($recapTickets2->getForm2()->toArray() as $form) {
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