<?php

namespace Louvre\TicketPlatformBundle\Controller;

use Louvre\TicketPlatformBundle\Model\FormModelStep1;
use Louvre\TicketPlatformBundle\Model\OwnerStep2;
use Louvre\TicketPlatformBundle\Model\PaymentModel;
use Louvre\TicketPlatformBundle\Type\OwnerStep2Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;



class TicketController extends Controller
{
    public function step1Action(Request $request)
    {

        //Création d'un objet FormModelStep1
        $formModelStep1 = new FormModelStep1();

        //Creation du formulaire  quand tout sera ok il faudra sortir le formulaire du controlleur
        $formStep1 = $this->get('form.factory')->createBuilder(FormType::class, $formModelStep1)
            ->add('visitDate', TextType::class)
            ->add('ticketType', ChoiceType::class, array(
                'choices' => array(
                    'Billet Journée' => 'Billet Journée',
                    'Billet Demie-journée' => 'Billet Demie-journée',
                )
            ))
            ->add('numberOfTickets', NumberType::class)
            ->add('email', EmailType::class)
            ->add('validation', SubmitType::class)
            ->getForm();


        //Requete
        if ($request->isMethod('POST')) {
            $formStep1->handleRequest($request);
            if ($formStep1->isValid()) {

                $session = $request->getSession();

                $session->set('formModelStep1', $formModelStep1);
                return $this->redirectToRoute('louvre_ticket_step_2');
            }
        }

        //Affichage du formulaire via méthode createView()
        return $this->render('LouvreTicketPlatformBundle:Ticket:Step1.html.twig', ['formView' => $formStep1
            ->createView(),]);


    }

    public function step2Action(Request $request)
    {
        $recapTickets1 = $request->getSession()->get('formModelStep1');
        dump($recapTickets1);
        //Récupération du nombre de tickets saisi à l'étape 1
       // $nbTickets = $request->getSession()->get('formModelStep1')->getNumberOfTickets();
        $nbTickets = $recapTickets1->getNumberOfTickets();
        dump($nbTickets);
        $ownerStep2 = new OwnerStep2($nbTickets);
        $form = $this->get('form.factory')->create(OwnerStep2Type::class, $ownerStep2);


        //Requete
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                //appel du service price
                //Parcours du array de données récupérées et usage du service
                $totalPrice = 0;
                foreach ($ownerStep2->getForm2() as $form) {

                    //Récupération du service de calcul de prix
                    $priceService = $this->get('louvre_ticketplatform.price_calculator');
                    //Calcul du prix de chaque ticket a l'aide du service
                    $finalPrice = $priceService->getPriceCalc($form->getBirthDate(),
                        $form->getReducedPrice(),
                        $recapTickets1->getTicketType(),
                        $this->getDoctrine()->getManager());

                    $form->setRealPrice($finalPrice);

                    //Calcul du prix total
                    $totalPrice += $form->getRealPrice();
                }
                $ownerStep2->setTotalPrice($totalPrice);

                //Mise en session des infos
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

        $stripeClient = $this->get('flosch.stripe.client');
        $token = $request->get('stripeToken');

        $newCustomer = 0;
        $errorMessage = 0;
        if ($token != NULL) {

            $newCustomer = $stripeClient->createCustomer($token, $request->get('cardName'));
            dump($newCustomer);
            $randomSuite = $this->get('louvre_ticketplatform.reservation_code');
            $amount = $recapTickets2->getTotalPrice().'00';
            $reservationCode = $randomSuite->generateRandomSuite();
            $chargeCard = $stripeClient->createCharge($amount, $chargeCurrency = 'EUR',
                $newCustomer, $reservationCode);
            dump($chargeCard);
            dump($chargeCard->status);

            if ($chargeCard->status == 'succeeded'){

            $paymentInfo = new PaymentModel();
            $paymentInfo->setCustomerId($newCustomer->id);
            $paymentInfo->setToken($token);
            $paymentInfo->setReservationCode($reservationCode);
            $session = $request->getSession();
            $session->set('paymentInfo', $paymentInfo);
            dump($paymentInfo);


            return $this->redirectToRoute('louvre_ticket_step_4');
            } else {

                $errorMessage = 'erreur';
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
        dump($recapPayment);

        if ($recapPayment->getReservationCode() != NULL) {
            $confirmationMail = $this->get('louvre_ticketplatform.confirmation_mail');
            $mail = $confirmationMail->generateMail($recapTickets1->getEmail(), $recapTickets1->getVisitDate(),
                $recapTickets2, $recapPayment->getReservationCode());
//            $message = \Swift_Message::newInstance()
//                ->setSubject('Confirmation de votre achat de billets')
//                ->setFrom('postmaster@maleyrie.fr')
//                ->setTo($recapTickets1->getEmail())
//                ->setBody(
//                    $this->renderView(
//                        'LouvreTicketPlatformBundle:Emails:ConfirmationMail.html.twig', [
//                            'recap2' => $recapTickets2,
//                            'visitDate' => $recapTickets1->getVisitDate()->format('Y-m-d'),
//                            'resCode' => $recapPayment->getReservationCode()
//                        ]
//                    ),
//                    'text/html'
//                )
//
//            ;
//            $this->get('mailer')->send($message);
//
//            //return $this->render(...);
        }




        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Step4.html.twig');

        return new Response($content);
    }

    public function step5Action()
    {

        //persistance en bdd de chaque instance à l'etape 5 (boucle à creer pour les nombres de tickets)
//                $ticket = new Ticket();
//                $ticket->setFirstName($formModel->getFirstName());
//                $em = $this->getDoctrine()->getManager();
//                $em->persist($ticket);
//                $em->flush();


//                //Récuperation du service
//                $formToEntity = $this->container->get('louvre_ticketplatform.formtoentityinjection');
//                //Execution du service
//                $formToEntity->injectionStep1(formStep1);

        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Step5.html.twig');

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