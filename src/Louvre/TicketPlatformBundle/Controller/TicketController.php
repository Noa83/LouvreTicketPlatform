<?php

namespace Louvre\TicketPlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function Step1Action()
    {
        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Step1.html.twig');

        return new Response($content);
    }

    public function Step2Action()
    {
        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Step2.html.twig');

        return new Response($content);
    }

    public function Step3Action()
    {
        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Step3.html.twig');

        return new Response($content);
    }

    public function  Step4Action()
    {
        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Step4.html.twig');

        return new Response($content);
    }

    public function Step5Action()
    {
        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Step5.html.twig');

        return new Response($content);
    }

    public function Step6Action()
    {
        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Step6.html.twig');

        return new Response($content);
    }

    public function CgvAction()
    {
        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Cgv.html.twig');

        return new Response($content);
    }

    public function TarifsAction()
    {
        $content = $this->get('templating')->render('LouvreTicketPlatformBundle:Ticket:Tarifs.html.twig');

        return new Response($content);
    }
}