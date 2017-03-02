<?php

namespace Louvre\TicketPlatformBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketControllerTest extends WebTestCase
{

    public function testStep1ActionCount()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/step-1-choice');

        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');

        $this->assertEquals(2, $crawler->filter('input[type="text"]')->count());
        $this->assertEquals(1, $crawler->filter('input[type="email"]')->count());
        $this->assertEquals(1, $crawler->filter('input[type="submit"]')->count());
        $this->assertEquals(1, $crawler->filter('select')->count());
    }

    public function testStep1ActionFormSubmit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/step-1-choice');

        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');

        $form = $crawler->selectButton('Validation')->form();


        $form['form_step1[visitDate]'] = '10/10/2017';
        $form['form_step1[ticketType]'] = 'Billet Journée';
        $form['form_step1[numberOfTickets]'] = '1';
        $form['form_step1[email]'] = 'toto@test.fr';


        $crawler = $client->submit($form);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Nom du visiteur")')->count()
        );
    }

}
