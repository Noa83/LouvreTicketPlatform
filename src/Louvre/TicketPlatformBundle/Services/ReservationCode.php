<?php

namespace Louvre\TicketPlatformBundle\Services;


class ReservationCode
{
    private $manager;

    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function generateRandomSuite()
    {
        $characts = 'abcdefghijklmnopqrstuvwxyz';
        $characts .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characts .= '1234567890';
        $randomSuite = '';

        while ($randomSuite == NULL) {
            for ($i = 0; $i < 15; $i++) {
                $randomSuite .= $characts[rand() % strlen($characts)];
            }

            $code = $this->manager->getRepository('LouvreTicketPlatformBundle:TicketOrder')
                ->getRepoBookingCode($randomSuite);

            if ($code != $randomSuite){
                return $randomSuite;
            }else{
                return $randomSuite = null;
            }
        }
        return $randomSuite;
    }
}
