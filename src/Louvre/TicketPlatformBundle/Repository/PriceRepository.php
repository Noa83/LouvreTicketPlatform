<?php

namespace Louvre\TicketPlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;



/**
 * PriceRepository
 */
class PriceRepository extends \Doctrine\ORM\EntityRepository
{
    public function getReducedPrice(){
        $redPrice = $this->_em
            ->getRepository('LouvreTicketPlatformBundle:Price')
            ->findOneBy(
                array('reducedPrice' => true)
            )
        ;
        dump($redPrice);
        return $redPrice;
    }

    public function getClassicsPrices(){
        $classicsPrices = $this->_em
            ->getRepository('LouvreTicketPlatformBundle:Price')
            ->findBy(
                array('reducedPrice' => false)
            );
        dump($classicsPrices);
        return $classicsPrices;
    }

}
