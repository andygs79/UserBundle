<?php
namespace AmpUserBundle\Source;

use AmpUserBundle\Source\Traits\GetSafeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @author Matt Holbrook-Bull <matt@ampisoft.com>
 *
 * Class AbstractUser
 * @package AmpUserBundle\Source
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
abstract class AbstractUser implements UserInterface {

    use GetSafeTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=250, nullable=false, unique=true)
     * @Assert\NotBlank()
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $plainPassword;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", nullable=false, unique=true)
     * @Assert\Email()
     */
    protected $email;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLoggedIn;

    /**
     * @ORM\Column(type="array")
     */
    protected $roles = [];

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enabled = true;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $guest = false;


    /**
     * @ORM\Column(type="string", nullable=true)
     *
     */
    protected $fullName = null;


    /**
     * User constructor.
     */
    public function __construct() {

        $seed = new \DateTime;

        $this->apiToken = openssl_digest( $seed->getTimestamp(), 'sha1' );
    }

    public function setUsername( $username ) {
        if ( !$this->fullName ) {
            $this->fullName = $username;
        }

        $this->username = $username;
    }

    /**
     * @return string
     */
    public function __toString() {
        return (string) $this->getUsername() . '-';
    }

    public function getFullName() {
        return $this->fullName;
    }


    public function setFullName( $fullName ) {
        $this->fullName = $fullName;
    }

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Url()
     * @Assert\NotBlank()
     */
    protected $url = null;

    /**
     * @ORM\Column(type="string")
     */
    protected $apiToken;

    public function isGranted($role) {
        return in_array($role, $this->getRoles());
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl( $url ) {
        $this->url = $url;
    }

    public function getGuest() {
        return $this->guest;
    }

    public function setGuest( $guest ) {
        $this->guest = $guest;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles() {
        return $this->roles;
    }

    public function addRole($role) {
        $this->roles[] = $role;
    }

    public function setRoles(array $roles) {
        foreach ( $roles as $role ) {
            $this->addRole($role);
        }
    }

    public function hasRole($role) {
        return in_array($role, $this->roles);
    }

    public function removeRole($role) {
        unset($this->roles[array_search($role, $this->roles)]);
    }

    public function getId() {
        return $this->id;
    }

    public function setId( $id ) {
        $this->id = $id;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername() {
        return $this->username;
    }

    public function getPlainPassword() {
        return $this->plainPassword;
    }

    public function setPlainPassword( $plainPassword ) {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword( $password ) {
        $this->password = $password;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSalt() {
        return $this->salt;
    }

    public function setSalt( $salt ) {
        $this->salt = $salt;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail( $email ) {
        $this->email = $email;
        return $this;
    }

    public function getLastLoggedIn() {
        return $this->lastLoggedIn;
    }

    public function setLastLoggedIn( $lastLoggedIn ) {
        $this->lastLoggedIn = $lastLoggedIn;

        return $this;
    }

    public function getEnabled() {
        return $this->enabled;
    }

    public function setEnabled( $enabled ) {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials() {
        $this->plainPassword = null;
    }

    public function getApiToken() {
        return $this->apiToken;
    }

    public function setApiToken( $apiToken ) {
        $this->apiToken = $apiToken;
    }
}