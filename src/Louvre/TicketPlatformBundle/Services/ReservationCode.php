<?php

namespace Louvre\TicketPlatformBundle\Services;

use Doctrine\ORM\Mapping as ORM;

class ReservationCode
{
    public function generateRandomSuite()
    {
        $characts = 'abcdefghijklmnopqrstuvwxyz';
        $characts .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characts .= '1234567890';
        $randomSuite = '';

        for($i=0;$i < 15;$i++)
        {
            $randomSuite .= $characts[ rand() % strlen($characts) ];
        }

        return $randomSuite;
    }
}