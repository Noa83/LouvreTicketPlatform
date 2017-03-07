<?php

namespace Louvre\TicketPlatformBundle\Controller;

use Louvre\TicketPlatformBundle\Model\FormModelStep1;
use Louvre\TicketPlatformBundle\Model\OwnerStep2;
use Louvre\TicketPlatformBundle\Model\PaymentModel;
use Louvre\TicketPlatformBundle\Form\Type\OwnerStep2Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Louvre\TicketPlatformBundle\Form\Type\FormStep1Type;


class TicketController extends Controller
{
    public function step1Action(Request $request)
    {
        $formModelStep1 = new FormModelStep1();
        $formStep1 = $this->get('form.factory')->create(FormStep1Type::class, $formModelStep1);

        if ($request->isMethod('POST')) {
            $formStep1->handleRequest($request);
            if ($formStep1->isValid()) {

                $session = $request->getSession();

                $session->set('formModelStep1', $formModelStep1);
                return $this->redirectToRoute('louvre_ticket_step_2');
            }
        }
        return $this->render('LouvreTicketPlatformBundle:Ticket:Step1.html.twig', ['formView' => $formStep1
            ->createView(),]);
    }

    public function step2Action(Request $request)
    {
        $recapTickets1 = $request->getSession()->get('formModelStep1');
        if (empty($recapTickets1)) {
            return $this->redirectToRoute('louvre_ticket_step_1');
        }
        $nbTickets = $recapTickets1->getNumberOfTickets();

        $ownerStep2 = new OwnerStep2($nbTickets);
        $form = $this->get('form.factory')->create(OwnerStep2Type::class, $ownerStep2);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $totalPrice = $this->get('louvre_ticketplatform.price_calculator')->totalPriceCalc($ownerStep2, $recapTickets1);

                $request->getSession()->set('ownerStep2', $ownerStep2);

                if ($totalPrice == 0) {
                    $reservationCode = $this->get('louvre_ticketplatform.reservation_code')->generateRandomSuite();
                    $paymentInfo = new PaymentModel(null, $reservationCode);
                    $request->getSession()->set('paymentInfo', $paymentInfo);
                    return $this->redirectToRoute('louvre_ticket_step_4');
                } else {
                    return $this->redirectToRoute('louvre_ticket_step_3');
                }
            }
        }
        return $this->render('LouvreTicketPlatformBundle:Ticket:Step2.html.twig', ['formView' => $form->createView(),]);
    }

    public function step3Action(Request $request)
    {
        $recapTickets1 = $request->getSession()->get('formModelStep1');
        $recapTickets2 = $request->getSession()->get('ownerStep2');
        if (empty($recapTickets1)) {
            return $this->redirectToRoute('louvre_ticket_step_1');
        }
        if (empty($recapTickets2)) {
            return $this->redirectToRoute('louvre_ticket_step_2');
        }
        $token = $request->get('stripeToken');
        if ($token != NULL) {

            try {
                $amount = $recapTickets2->getTotalPrice() . '00';
                $this->get('louvre_ticketplatform.stripe_payment')->createCharge($amount, 'EUR', $token);

                $reservationCode = $this->get('louvre_ticketplatform.reservation_code')->generateRandomSuite();

                $paymentInfo = new PaymentModel($token, $reservationCode);
                $request->getSession()->set('paymentInfo', $paymentInfo);
                return $this->redirectToRoute('louvre_ticket_step_4');

            } catch (\Stripe\Error\Card $e) {
                $errorMessage = "Une erreur s'est produite, veuillez recommencer votre paiement";
            }
        }
        return $this->render('LouvreTicketPlatformBundle:Ticket:Step3.html.twig', [
            'recap1' => $recapTickets1,
            'recap2' => $recapTickets2,
            'errorMessage' => $errorMessage
        ]);
    }

    public function step4Action(Request $request)
    {
        $recapTickets1 = $request->getSession()->get('formModelStep1');
        $recapTickets2 = $request->getSession()->get('ownerStep2');
        $recapPayment = $request->getSession()->get('paymentInfo');
        if (empty($recapTickets1)) {
            return $this->redirectToRoute('louvre_ticket_step_1');
        }
        if (empty($recapTickets2)) {
            return $this->redirectToRoute('louvre_ticket_step_2');
        }
        if (empty($recapPayment)) {
            return $this->redirectToRoute('louvre_ticket_step_3');
        }

        if ($recapPayment->getReservationCode() != NULL) {
            $this->get('louvre_ticketplatform.formtoentity')
                ->bddRecording($recapTickets1, $recapTickets2, $recapPayment);

            $this->get('louvre_ticketplatform.confirmation_mail')
                ->generateMail($recapTickets1->getEmail(), $recapTickets1->getVisitDate(),
                    $recapTickets2, $recapPayment->getReservationCode());

            $request->getSession()->invalidate();
        }
        return $this->render('LouvreTicketPlatformBundle:Ticket:Step4.html.twig');

    }

    public function cgvAction()
    {
        return $this->render('LouvreTicketPlatformBundle:Ticket:Cgv.html.twig');
    }

    public function tarifsAction()
    {
        return $this->render('LouvreTicketPlatformBundle:Ticket:Tarifs.html.twig');
    }

    public function mailAction()
    {
        return $this->render('LouvreTicketPlatformBundle:Emails:ConfirmationMail.mail.twig');
    }
}