<?php

namespace Louvre\TicketPlatformBundle\Tests\Services;


use Louvre\TicketPlatformBundle\Services\PriceCalculator;
use Louvre\TicketPlatformBundle\Entity\Price;
use Louvre\TicketPlatformBundle\Repository\PriceRepository;
use Doctrine\Common\Persistence\ObjectManager;

class PriceCalculatorTest extends \PHPUnit_Framework_TestCase
{
    const REDUCED_PRICE = 10;
    const NORMAL_PRICE = 16;
    const NORMAL_HALF_D_PRICE = self::NORMAL_PRICE / 2;
    private $service;
    private $birthDate;

    protected function setUp()
    {
        $this->birthDate = \DateTime::createFromFormat('d/m/Y', '27/03/1983');

        $priceReducedTest = $this->createMock(Price::class);
        $priceReducedTest//->expects($this->once())
        ->method('getPrice')
            ->will($this->returnValue(self::REDUCED_PRICE));

        $priceTest = new Price();
        $priceTest->setMaxAge('59');
        $priceTest->setMinAge('12');
        $priceTest->setPrice(self::NORMAL_PRICE);
        $priceTest->setReducedPrice('false');
        $priceTest = ['price' => $priceTest];

        $priceRepository = $this
            ->getMockBuilder(PriceRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $priceRepository//->expects($this->any())
        ->method('getClassicsPrices')
            ->will($this->returnValue($priceTest));
        $priceRepository//->expects($this->once())
        ->method('getReducedPrice')
            ->will($this->returnValue($priceReducedTest));

        $entityManager = $this
            ->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager//->expects($this->any())
        ->method('getRepository')
            ->will($this->returnValue($priceRepository));


        $this->service = new PriceCalculator($entityManager);

    }


    public function testGetPriceCalcWhenReducedPrice()
    {
        $result = $this->service->getPriceCalc($this->birthDate, true, 'Billet journée');
        $this->assertEquals($result, self::REDUCED_PRICE);
    }

    public function testGetPriceCalWhenHalfDayTicket()
    {
        $result = $this->service->getPriceCalc($this->birthDate, false, 'Billet Demie-journée');
        $this->assertEquals($result, self::NORMAL_HALF_D_PRICE);
    }

    public function testGetPriceCalWithNoSpecs()
    {
        $result = $this->service->getPriceCalc($this->birthDate, false, 'Billet journée');
        $this->assertEquals($result, self::NORMAL_PRICE);
    }
}
