<?php

namespace Louvre\TicketPlatformBundle\Services;

use Doctrine\ORM\Mapping as ORM;




class ConfirmationMail
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var \Twig_Environment
     */
    private $twig;


    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig )
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function generateMail($email, $visitDate, $recapTickets2, $reservationCode)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Confirmation de votre réservation')
            ->setFrom('postmaster@maleyrie.fr')
            ->setTo($email)
            ->setBody(
                $this->twig->render(
                    'LouvreTicketPlatformBundle:Emails:ConfirmationMail.mail.twig', [
                        'recap2' => $recapTickets2,
                        'visitDate' => $visitDate,
                        'resCode' => $reservationCode
                    ]
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);

    }


}