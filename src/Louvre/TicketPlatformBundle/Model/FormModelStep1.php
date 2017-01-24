<?php

namespace Louvre\TicketPlatformBundle\Model;

use Doctrine\ORM\Mapping as ORM;


/**
 * FormModelStep1
 *
 * @ORM\MappedSuperClass
 */

class FormModelStep1
{

    private $visitDate;
    private $ticketType;
    private $numberOfTickets;
    private $email;


    public function __construct()
    {
        $this->visitDate = new \DateTime();
    }


    /**
     * Set visitDate
     *
    @param \DateTime $visitDate
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
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
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