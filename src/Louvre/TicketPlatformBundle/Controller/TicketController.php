<?php

namespace Louvre\TicketPlatformBundle\Controller;

use Louvre\TicketPlatformBundle\Entity\Ticket;
use Louvre\TicketPlatformBundle\Model\FormModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Louvre\TicketPlatformBundle\FormToEntity;

class TicketController extends Controller
{
    public function step1Action(Request $request)
    {
        //Création d'un objet FormModel
        $formModel = new FormModel();

        //Creation du formulaire
        $formStep1 = $this->get('form.factory')->createBuilder(FormType::class, $formModel)
            ->setAction($this->generateUrl('louvre_ticket_step_2'))
            ->add('visitDate',        DateType::class)
            ->add('ticketType',       ChoiceType::class, array(
                'choices' =>  array(
                    'Billet Journée'        => 'fullDayTicket',
                    'Billet Demi-journée'   => 'halfDayTicket',
                )
            ))
            ->add('numberOfTickets',  ChoiceType::class, [
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                ]
            ])
            ->add('email',            EmailType::class)
            ->add('validation',       SubmitType::class)
            ->getForm()
        ;

        //Requete
        if ($request->isMethod('POST')) {
            $formStep1->handleRequest($request);
            if ($formStep1->isValid()) {

//
            }
        }

        //Affichage du formulaire via méthode createView()
        return $this->render('LouvreTicketPlatformBundle:Ticket:Step1.html.twig', ['formView' => $formStep1->createView(),]);


    }

    public function step2Action()
    {
        //Création d'une boucle en fonction du nombre de tickets demandés à l'étape 1

        //Création d'un objet FormModel
        $formModel = new FormModel();



        //Ajout des champs souhaités pour le formulaire
        $formStep2 = $this->get('form.factory')->createBuilder(FormType::class, $formModel)
            ->add('name',           TextType::class)
            ->add('firstName',      TextType::class)
            ->add('birthDate',      BirthdayType::class)
            ->add('country',        CountryType::class)
            ->add('reducedPrice',   CheckboxType::class)
            ->add('validation',     SubmitType::class)
            ->getForm()
        ;

        //Affichage du formulaire via méthode createView()
        return $this->render('LouvreTicketPlatformBundle:Ticket:Step1.html.twig', ['formView' => $formStep2->createView()]);
    }

    public function step3Action()
    {
        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Step3.html.twig');

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