<?php

namespace Louvre\TicketPlatformBundle\Repository;


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
        return $redPrice;
    }

    public function getClassicsPrices(){
        $classicsPrices = $this->_em
            ->getRepository('LouvreTicketPlatformBundle:Price')
            ->findBy(
                array('reducedPrice' => false)
            );
        return $classicsPrices;
    }

}
