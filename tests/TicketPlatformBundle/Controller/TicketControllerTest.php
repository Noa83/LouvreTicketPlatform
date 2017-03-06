<?php

namespace Louvre\TicketPlatformBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketControllerTest extends WebTestCase
{

    public function testStep1ActionCount()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');

        $this->assertEquals(2, $crawler->filter('input[type="text"]')->count());
        $this->assertEquals(1, $crawler->filter('input[type="email"]')->count());
        $this->assertEquals(1, $crawler->filter('input[type="submit"]')->count());
        $this->assertEquals(1, $crawler->filter('select')->count());
    }

    public function testStep1ActionFormSubmit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');

        $form = $crawler->selectButton('Validation')->form();


        $form['form_step1[visitDate]'] = '10/10/2017';
        $form['form_step1[ticketType]'] = 'Billet Journée';
        $form['form_step1[numberOfTickets]'] = '1';
        $form['form_step1[email]'] = 'toto@test.fr';


        $client->submit($form);

        $this->assertTrue(
            $client->getResponse()->isRedirect('/infos'),
            'response is a redirect to /infos');

        $this->assertEquals(
            'toto@test.fr',
            $client->getRequest()->getSession()->get('formModelStep1')->getEmail()
        );

    }

}
