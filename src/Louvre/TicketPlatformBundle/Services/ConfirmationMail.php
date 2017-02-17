<?php

namespace Louvre\TicketPlatformBundle\Services;

use Doctrine\ORM\Mapping as ORM;




class ConfirmationMail
{
    private $mailer;
    private $locale;

    public function __construct(\Swift_Mailer $mailer, $locale )
    {
        $this->mailer = $mailer;
        $this->locale = $locale;

    }

    public function generateMail($email, $visitDate, $recapTickets2, $reservationCode)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Confirmation de votre réservation')
            ->setFrom('postmaster@maleyrie.fr')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'Emails/ConfirmationMail.html.twig', [
                        'recap2' => $recapTickets2,
                        'visitDate' => $visitDate,
                        'resCode' => $reservationCode
                    ]
                ),
                'text/html'
            )

        ;
        $this->get('mailer')->send($message);

        return $this->render('Ticket/Step4.html.twig');
    }


}