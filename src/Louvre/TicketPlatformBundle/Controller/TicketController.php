<?php

namespace Louvre\TicketPlatformBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Louvre\TicketPlatformBundle\Entity\Ticket;
use Louvre\TicketPlatformBundle\Model\FormModelStep1;
use Louvre\TicketPlatformBundle\Model\FormModelStep2;
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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Louvre\TicketPlatformBundle\FormToEntity;
use Louvre\TicketPlatformBundle\Model\FormModelConteneurStep2;

class TicketController extends Controller
{
    public function step1Action(Request $request)
    {

        //Création d'un objet FormModelStep1
        $formModelStep1 = new FormModelStep1();

        //Creation du formulaire  quand tout sera ok il faudra sortir le formulaire du controlleur
        $formStep1 = $this->get('form.factory')->createBuilder(FormType::class, $formModelStep1)
            //->setAction($this->generateUrl('louvre_ticket_step_2'))
            ->add('visitDate',        DateType::class)
            ->add('ticketType',       ChoiceType::class, array(
                'choices' =>  array(
                    'Billet Journée'        => 'fullDayTicket',
                    'Billet Demi-journée'   => 'halfDayTicket',
                )
            ))
            ->add('numberOfTickets',  NumberType::class)
            ->add('email',            EmailType::class)
            ->add('validation',       SubmitType::class)
            ->getForm()
        ;



        //Requete                mettre du dump partout pour tester et visualiser l'etat de mes variables.
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

        $myForm = $this->get('form.factory')->createBuilder();
        $formModelStep2 = new ArrayCollection();
        //Création de la boucle variant en fonction du nb de tickets saisi
        for($i =0; $i<$nbTickets; $i++){



            //Création du formulaire répété
            $formTicketOwnerIdentity = $this->get('form.factory')->createBuilder($i, FormType::class, $formModelStep2)
            ->add('name',           TextType::class)
            ->add('firstName',      TextType::class)
            ->add('birthDate',      BirthdayType::class)
            ->add('country',        CountryType::class)
            ->add('reducedPrice',   CheckboxType::class);

            //Insertion du formulaire répété dans le formulaire conteneur
            $myForm->add($formTicketOwnerIdentity);
        }
        //Ajout du bouton validation et Création du formulaire total.
        $myForm->add('validation',     SubmitType::class);
        $myForm = $myForm->getForm();

        dump($myForm);

        //Affichage du formulaire via méthode createView()
        return $this->render('LouvreTicketPlatformBundle:Ticket:Step1.html.twig', ['formView' => $myForm
            ->createView(),]);
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