<?php


namespace Louvre\TicketPlatformBundle\Model;

use Doctrine\ORM\Mapping as ORM;
/**
 * PaymentModel
 *
 * @ORM\MappedSuperClass
 */
class PaymentModel
{

   private $token;
   private $reservationCode;

    /**
     * @return mixed
     */
    public function getReservationCode()
    {
        return $this->reservationCode;
    }

    /**
     * @param mixed $reservationCode
     */
    public function setReservationCode($reservationCode)
    {
        $this->reservationCode = $reservationCode;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }



}