<?php

namespace Louvre\TicketPlatformBundle\Model;

use Doctrine\ORM\Mapping as ORM;


/**
 * FormModelStep2
 *
 * @ORM\MappedSuperClass
 */

class FormModelStep2
{
    private $name;
    private $firstName;
    private $birthDate;
    private $country;
    private $reducedPrice;

    public function __construct()
    {
        $this->birthDate = new \DateTime();

    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return FormModelStep2
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return FormModelStep2
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set birthDate
     *
    @param \DateTime $birthDate
     *
     * @return FormModelStep2
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return FormModelStep2
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set reducedPrice
     *
     * @param boolean $reducedPrice
     *
     * @return FormModelStep2
     */
    public function setReducedPrice($reducedPrice)
    {
        $this->reducedPrice = $reducedPrice;

        return $this;
    }

    /**
     * Get reducedPrice
     *
     * @return bool
     */
    public function getReducedPrice()
    {
        return $this->reducedPrice;
    }
}