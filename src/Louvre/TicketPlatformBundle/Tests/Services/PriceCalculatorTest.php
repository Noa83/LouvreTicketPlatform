<?php

namespace Louvre\TicketPlatformBundle\Tests\Services;


use Louvre\TicketPlatformBundle\Services\PriceCalculator;
use Louvre\TicketPlatformBundle\Entity\Price;
use Louvre\TicketPlatformBundle\Repository\PriceRepository;
use Doctrine\Common\Persistence\ObjectManager;

class PriceCalculatorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPriceCalcWhenReducedPrice()
    {
        $expectedPrice = 10;
        $price = $this->createMock(Price::class);
        $price->expects($this->once())
            ->method('getPrice')
            ->will($this->returnValue($expectedPrice));

        $priceRepository = $this
            ->getMockBuilder(PriceRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $priceRepository->expects($this->once())
            ->method('getReducedPrice')
            ->will($this->returnValue($price));

        $entityManager = $this
            ->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($priceRepository));



        $birthDate = \DateTime::createFromFormat('d/m/Y', '27/03/1983');

        $priceCal = new PriceCalculator($entityManager);
        $result = $priceCal->getPriceCalc($birthDate, true, 'unused' );
        $this->assertEquals($result, $expectedPrice);


    }
}
