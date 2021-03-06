<?php

namespace Application\GlobalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prototype
 * @ORM\MappedSuperclass
 */
class BasePrototype
{
    /**
     * @var string
     * @ORM\Column(type="string", length=80)
     */
    protected $name;

    /**
     * @var \DateTime
     */
    protected $timestamp;

    /**
     * @var string
     * @ORM\Column(type="string", length=40)
     */
    protected $user;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
    }

    /**
     * Set name
     *
     * @param  string    $name
     * @return Prototype
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
     * Set user
     *
     * @param  string    $user
     * @return Prototype
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
