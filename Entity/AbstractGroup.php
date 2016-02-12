<?php
namespace Ampisoft\UserBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @author Matt Holbrook-Bull <matt@ampisoft.com>
 *
 * Class Group
 * @package Ampisoft\UserBundle\Entity
 */
abstract class AbstractGroup implements \Serializable{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="array")
     */
    protected $roles = [ ];

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isActive = true;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="groups")
     */
    protected $users;

    public function __construct() {
        $this->users = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName( $name ) {
        $this->name = $name;

        return $this;
    }

    public function getRoles() {
        return $this->roles;
    }

    public function addRole( $role ) {
        $this->roles[] = strtoupper( $role );

        return $this;
    }

    /**
     * @param $role
     * @return bool
     */
    public function removeRole( $role ) {
        $index = array_search( strtoupper( $role ), $this->roles );
        if ( $index ) {
            unset( $this->roles[ $index ] );

            return true;
        }

        return false;
    }

    public function setRoles( array $roles ) {
        $this->roles = $roles;

        return $this;
    }

    public function isActive() {
        return $this->isActive;
    }

    public function setIsActive( $isActive ) {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @inheritDoc
     */
    abstract public function serialize();

    /**
     * @inheritDoc
     */
    abstract public function unserialize( $serialized );

    
}