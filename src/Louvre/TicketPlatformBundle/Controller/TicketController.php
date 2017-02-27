<?php

namespace Louvre\TicketPlatformBundle\Controller;

use Louvre\TicketPlatformBundle\Model\FormModelStep1;
use Louvre\TicketPlatformBundle\Model\OwnerStep2;
use Louvre\TicketPlatformBundle\Model\PaymentModel;
use Louvre\TicketPlatformBundle\Type\OwnerStep2Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Louvre\TicketPlatformBundle\Type\FormStep1Type;


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
        $nbTickets = $recapTickets1->getNumberOfTickets();

        $ownerStep2 = new OwnerStep2($nbTickets);
        $form = $this->get('form.factory')->create(OwnerStep2Type::class, $ownerStep2);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $totalPrice = 0;
                foreach ($ownerStep2->getForm2() as $form) {
                    //Calcul du prix de chaque ticket a l'aide du service
                    $finalPrice = $this->get('louvre_ticketplatform.price_calculator')
                        ->getPriceCalc($form->getBirthDate(),
                        $form->getReducedPrice(),
                        $recapTickets1->getTicketType());

                    $form->setRealPrice($finalPrice);

                    //Calcul du prix total
                    $totalPrice += $form->getRealPrice();
                }
                $ownerStep2->setTotalPrice($totalPrice);

                $session = $request->getSession();
                $session->set('ownerStep2', $ownerStep2);

                return $this->redirectToRoute('louvre_ticket_step_3');
            }
        }
        return $this->render('LouvreTicketPlatformBundle:Ticket:Step2.html.twig', ['formView' => $form->createView(),]);
    }

    public function step3Action(Request $request)
    {
        $recapTickets1 = $request->getSession()->get('formModelStep1');
        $recapTickets2 = $request->getSession()->get('ownerStep2');

        $token = $request->get('stripeToken');
        if ($token != NULL) {

            try {
                $amount = $recapTickets2->getTotalPrice() . '00';
                $this->get('louvre_ticketplatform.stripe_payment')->createCharge($amount, 'EUR', $token);

                $reservationCode = $this->get('louvre_ticketplatform.reservation_code')->generateRandomSuite();

                $paymentInfo = new PaymentModel();
                $paymentInfo->setToken($token);
                $paymentInfo->setReservationCode($reservationCode);
                $session = $request->getSession();
                $session->set('paymentInfo', $paymentInfo);
                return $this->redirectToRoute('louvre_ticket_step_4');

            } catch (\Stripe\Error\Card $e) {
                $errorMessage = $e->getMessage();
                return $this->render('LouvreTicketPlatformBundle:Ticket:Step3.html.twig', [
                    'recap1' => $recapTickets1,
                    'recap2' => $recapTickets2,
                    'errorMessage' => $errorMessage
                ]);
            }
        }
        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Step3.html.twig', [
            'recap1' => $recapTickets1,
            'recap2' => $recapTickets2
        ]);
        return new Response($content);
    }

    public function step4Action(Request $request)
    {
        $recapTickets1 = $request->getSession()->get('formModelStep1');
        $recapTickets2 = $request->getSession()->get('ownerStep2');
        $recapPayment = $request->getSession()->get('paymentInfo');

        if ($recapPayment->getReservationCode() != NULL) {
            $this->get('louvre_ticketplatform.confirmation_mail')
                ->generateMail($recapTickets1->getEmail(), $recapTickets1->getVisitDate(),
                    $recapTickets2, $recapPayment->getReservationCode());

            $this->get('louvre_ticketplatform.formtoentity')
                ->bddRecording($recapTickets1, $recapTickets2, $recapPayment);
        }
        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Step4.html.twig');

        return new Response($content);
    }


    public function cgvAction()
    {
        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Cgv.html.twig');

        return new Response($content);
    }

    public function tarifsAction()
    {
        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Tarifs.html.twig');

        return new Response($content);
    }

    public function mailAction()
    {
        return $this->render('LouvreTicketPlatformBundle:Ticket:mailtrame.html.twig');
    }
}