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
    private $name;
    private $cardNumber;
    private $expMonth;
    private $expYear;
    private $validationCode;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @param mixed $cardNumber
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
    }

    /**
     * @return mixed
     */
    public function getExpMonth()
    {
        return $this->expMonth;
    }

    /**
     * @param mixed $expMonth
     */
    public function setExpMonth($expMonth)
    {
        $this->expMonth = $expMonth;
    }

    /**
     * @return mixed
     */
    public function getExpYear()
    {
        return $this->expYear;
    }

    /**
     * @param mixed $expYear
     */
    public function setExpYear($expYear)
    {
        $this->expYear = $expYear;
    }

    /**
     * @return mixed
     */
    public function getValidationCode()
    {
        return $this->validationCode;
    }

    /**
     * @param mixed $validationCode
     */
    public function setValidationCode($validationCode)
    {
        $this->validationCode = $validationCode;
    }




}