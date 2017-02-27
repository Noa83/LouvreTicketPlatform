<?php

namespace Louvre\TicketPlatformBundle\Services;

use Doctrine\ORM\Mapping as ORM;
use Louvre\TicketPlatformBundle\Entity\Price;

class PriceCalculator
{

    private $manager;

    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function getPriceCalc($birthDate, $reducedPrice, $ticketType)
    {

        //Calcul de l'age
        list($year, $month, $day) = explode('-', $birthDate->format('Y-m-d'));
        $date['month'] = date('m');
        $date['day'] = date('d');
        $date['year'] = date('Y');
        $years = $date['year'] - $year;
        if ($month >= $date['month']) {
            if ($month == $date['month']) {
                if ($day > $date['day']) $years--;
            } else {
                $years--;
            }
        };
        $age = $years;


        //Calcul du prix
        $priceRepository = $this->manager->getRepository('LouvreTicketPlatformBundle:Price');

        if ($reducedPrice) {
            /** @var Price $halfPrice */
            $halfPrice = $priceRepository->getReducedPrice();
            return $goodPrice = $halfPrice->getPrice();

        } else {

            $classicsPrices = $priceRepository->getClassicsPrices();

            foreach ($classicsPrices as $clasPrice) {

                if ($clasPrice->getMinAge() <= $age && $age <= $clasPrice->getMaxAge()) {
                    $goodPrice = $clasPrice->getPrice();

                    //Ajustement en fonction du type de billet
                    if ($ticketType == 'Billet Demie-journée') {
                        return $finalPrice = $goodPrice / 2;
                    } else {
                        return $finalPrice = $goodPrice;
                    }
                };
            };
        }
    }
}


