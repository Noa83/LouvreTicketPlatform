<?php

namespace Louvre\TicketPlatformBundle\PriceCalculator;

use Doctrine\ORM\Mapping as ORM;
use Louvre\TicketPlatformBundle\Entity\Price;

class PriceCalculator
{
    public function getPriceCalc($age, $reducedPrice, $manager)
    {
        $priceRepository = $manager->getRepository('LouvreTicketPlatformBundle:Price');

        if ($reducedPrice) {
            /** @var Price $redPrice */
            $redPrice = $priceRepository->getReducedPrice();
            dump($redPrice);
            return $redPrice->getPrice();

        } else {

            $classicsPrices = $priceRepository->getClassicsPrices();
            dump($classicsPrices);

            foreach ($classicsPrices as $clasPrice) {

                if ($clasPrice->getMinAge() <= $age && $age <= $clasPrice->getMaxAge()) {
                    return $clasPrice->getPrice();
                };
            };

        }
    }
}


