<?php

namespace Louvre\TicketPlatformBundle\Controller;

use Louvre\TicketPlatformBundle\Model\FormModelStep1;
use Louvre\TicketPlatformBundle\Model\OwnerStep2;
use Louvre\TicketPlatformBundle\Type\OwnerStep2Type;
use Louvre\TicketPlatformBundle\PriceCalculator\PriceCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Repository;



class TicketController extends Controller
{
    public function step1Action(Request $request)
    {

        //Création d'un objet FormModelStep1
        $formModelStep1 = new FormModelStep1();

        //Creation du formulaire  quand tout sera ok il faudra sortir le formulaire du controlleur
        $formStep1 = $this->get('form.factory')->createBuilder(FormType::class, $formModelStep1)
            ->add('visitDate',        DateType::class)
            ->add('ticketType',       ChoiceType::class, array(
                'choices' =>  array(
                    'Billet Journée'        => 'Billet Journée',
                    'Billet Demie-journée'   => 'Billet Demie-journée',
                )
            ))
            ->add('numberOfTickets',  NumberType::class)
            ->add('email',            EmailType::class)
            ->add('validation',       SubmitType::class)
            ->getForm()
        ;



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
        //Récupération du nombre de tickets saisi à l'étape 1
        $nbTickets = $request->getSession()->get('formModelStep1')->getNumberOfTickets();
        dump($nbTickets);

        $ownerStep2 = new OwnerStep2($nbTickets);
        $form   = $this->get('form.factory')->create(OwnerStep2Type::class, $ownerStep2);


        //Requete
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $session = $request->getSession();

                $session->set('ownerStep2', $ownerStep2);
                dump($ownerStep2);

                return $this->redirectToRoute('louvre_ticket_step_3');
            }
        }



        return $this->render('LouvreTicketPlatformBundle:Ticket:Step2.html.twig', ['formView' => $form->createView(),]);

    }

    public function step3Action(Request $request)
    {
        //Récupération des infos saisies lors des étapes précedentes:
        $recapTickets1 = $request->getSession()->get('formModelStep1');
        $recapTickets2 = $request->getSession()->get('ownerStep2');


        //Parcours du array de données récupérées et usage des services
        foreach ($recapTickets2->getForm2() as $form) {
            //Récupération de l'age via le service dédié
            $ageService = $this->get('louvre_ticketplatform.age_calculator');
            //Calcul de l'âge de chaque titulaire d'un billet
            $age = $ageService->getAgeFromBirthDate($form->getBirthDate());


            //Récupération du service de calcul de prix
            $priceService = $this->get('louvre_ticketplatform.price_calculator');
            //Calcul du prix de chaque ticket
            $redPrice = $form->getReducedPrice();
            $goodPrice = $priceService->getPriceCalc($age, $redPrice, $this->getDoctrine()->getManager());
            if ($recapTickets1->getTicketType() == 'Billet Demie-journée') {
                $finalPrice = $goodPrice / 2;
            } else {
                $finalPrice = $goodPrice;
            }

            $form->setRealPrice($finalPrice);

            //Calcul du prix total
            $totalPrice = 0;
            foreach ($recapTickets2->getForm2() as $form) {
                $totalPrice += $form->getRealPrice();
            }




        }
        //Creation du bouton d'envoi sur la page suivante
        //Création d'un objet FormModelStep1
        $formModelStep1 = new FormModelStep1();

        //Creation du formulaire  quand tout sera ok il faudra sortir le formulaire du controlleur
        $formStep3 = $this->get('form.factory')->createBuilder(FormType::class, $formModelStep1)
            ->add('Paiement', SubmitType::class)
            ->add('Précédent', SubmitType::class, array(
                'validation_groups' => false)
            ->getForm()
        ;

        //Requete
        if ($request->isMethod('POST')) {
            $formStep3->handleRequest($request);
            if ($formStep3->isValid()) {

                $session = $request->getSession();
                $session->set('totalPrice', $totalPrice);

                return $this->redirectToRoute('louvre_ticket_step_4');
            }
        }



        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Step3.html.twig', [
            'recap1' => $recapTickets1,
            'recap2' => $recapTickets2,
            'finalPrice' => $finalPrice,
            'totalPrice' => $totalPrice,
            'submit'    => $formStep3
        ]);

//        $session = $request->getSession();
//        $session->set('totalPrice', $totalPrice);


        return new Response($content);
    }

    public function  step4Action()
    {
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

    public function step6Action()
    {
        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Step6.html.twig');

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
}