<?php

namespace Louvre\TicketPlatformBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Louvre\TicketPlatformBundle\Validator\Constraints as LouvreAssert;


/**
 * FormModelStep1
 *
 * @ORM\MappedSuperClass
 *
 * @LouvreAssert\LimitReached
 * @LouvreAssert\MaxHourExceeded
 */

class FormModelStep1
{
    private $visitDate;
    private $ticketType;
    private $numberOfTickets;
    private $email;


    /**
     * Set visitDate
     *
     * @param string $visitDate
     *
     * @return FormModelStep1
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate
     *
     * @return string
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * Get formatedVisitDate
     *
     * @return \DateTime
     */
    public function getFormatedVisitDate()
    {
        $format = 'd/m/Y';
        return \DateTime::createFromFormat($format, $this->visitDate);
    }


    /**
     * Set ticketType
     *
     * @param string $ticketType
     *
     * @return FormModelStep1
     */
    public function setTicketType($ticketType)
    {
        $this->ticketType = $ticketType;

        return $this;
    }

    /**
     * Get ticketType
     *
     * @return string
     */
    public function getTicketType()
    {
        return $this->ticketType;
    }

    /**
     * Set numberOfTickets
     *
     * @param integer $numberOfTickets
     *
     * @return FormModelStep1
     */
    public function setNumberOfTickets($numberOfTickets)
    {
        $this->numberOfTickets = $numberOfTickets;

        return $this;
    }

    /**
     * Get numberOfTickets
     *
     * @return int
     */
    public function getNumberOfTickets()
    {
        return $this->numberOfTickets;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return FormModelStep1
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

}