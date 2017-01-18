<?php

namespace Louvre\TicketPlatformBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormModel
 *
 * @ORM\MappedSuperClass
 */

abstract class FormModel
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visitDate", type="datetime")
     */
    private $visitDate;

    /**
     * @var string
     *
     * @ORM\Column(name="ticketType", type="string", length=255)
     */
    private $ticketType;

    /**
     * @var int
     *
     * @ORM\Column(name="numberOfTickets", type="integer")
     */
    private $numberOfTickets;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthDate", type="datetime")
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var bool
     *
     * @ORM\Column(name="reducedPrice", type="boolean")
     */
    private $reducedPrice;

}