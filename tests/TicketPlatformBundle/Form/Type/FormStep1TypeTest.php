<?php
namespace Louvre\TicketPlatformBundle\Tests\Form\Type;


use Louvre\TicketPlatformBundle\Model\FormModelStep1;
use Louvre\TicketPlatformBundle\Form\Type\FormStep1Type;
use Symfony\Component\Form\Test\TypeTestCase;


class FormStep1TypeTest extends TypeTestCase
{
    public function testSubmitFormStep1Action()
    {
        $formData = [
            'visitDate' => '10/03/2017',
            'ticketType' => 'Billet Journée',
            'numberOfTickets' => '2',
            'email' => 'toto@test.fr'
        ];

        $model = new FormModelStep1();
        $model->setVisitDate('10/03/2017');
        $model->setTicketType('Billet Journée');
        $model->setNumberOfTickets('2');
        $model->setEmail('toto@test.fr');

        $form = $this->factory->create(FormStep1Type::class);


        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($model, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }

    }
}
