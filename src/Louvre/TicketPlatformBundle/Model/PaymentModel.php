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
   private $customerId;
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

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param mixed $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

}