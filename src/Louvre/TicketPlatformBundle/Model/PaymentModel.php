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
     * PaymentModel constructor.
     * @param $token
     * @param $reservationCode
     */
    public function __construct($token, $reservationCode)
    {
        $this->token = $token;
        $this->reservationCode = $reservationCode;
    }


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