<?php

namespace Louvre\TicketPlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * TicketOrder
 *
 * @ORM\Table(name="ticket_order")
 * @ORM\Entity(repositoryClass="Louvre\TicketPlatformBundle\Repository\TicketOrderRepository")
 */
class TicketOrder
{
    /**
     * @ORM\OneToMany(targetEntity="Louvre\TicketPlatformBundle\Entity\Ticket", mappedBy = "ticketOrder", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $tickets;


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="bookingCode", type="string", length=255)
     */
    private $bookingCode;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set bookingCode
     *
     * @param string $bookingCode
     *
     * @return TicketOrder
     */
    public function setBookingCode($bookingCode)
    {
        $this->bookingCode = $bookingCode;

        return $this;
    }

    /**
     * Get bookingCode
     *
     * @return string
     */
    public function getBookingCode()
    {
        return $this->bookingCode;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return TicketOrder
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ticket
     *
     * @param \Louvre\TicketPlatformBundle\Entity\Ticket $ticket
     *
     * @return TicketOrder
     */
    public function addTicket(\Louvre\TicketPlatformBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \Louvre\TicketPlatformBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\Louvre\TicketPlatformBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
}
