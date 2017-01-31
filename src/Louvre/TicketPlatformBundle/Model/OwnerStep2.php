<?php

namespace Louvre\TicketPlatformBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * OwnerStep2
 *
 * @ORM\MappedSuperClass
 */

class OwnerStep2
{
    private $form2;

    private $totalPrice;

    /**
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param mixed $totalPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    public function __construct($nbTickets)
    {
    $this->form2 = new ArrayCollection();
        for($i=0; $i<$nbTickets; $i++)
        {
            $this->form2[$i]= new FormModelStep2();
        }
    }

    public function setForm2($form2)
    {
        $this->form2 = $form2;
        return $this;
    }

    public function getForm2()
    {
        return $this->form2;
    }
}