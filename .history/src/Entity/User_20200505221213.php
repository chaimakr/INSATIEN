<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 * fields = {"email"},
 * message = "the email you typed is already in use"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Regex(
     * pattern="#@insat.u-carthage.tn#",
     * message="use your insat.u-carthage mail ya haj")
     */
    private $email;


    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Length(min="8",minMessage="your password should contain at least 8 characters !")
     */


    private $password;
    
    /**
     * @Assert\EqualTo(propertyPath="password" , message="Passwords do no match")
     */
    private $confirmPassword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $confirmationCode;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $registerAs;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function getconfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }
    public function setconfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    public function getConfirmationCode(): ?string
    {
        return $this->confirmationCode;
    }

    public function setConfirmationCode(string $confirmationCode): self
    {
        $this->confirmationCode = $confirmationCode;

        return $this;
    }

    public function getRegisterAs(): ?string
    {
        return $this->registerAs;
    }

    public function setRegisterAs(string $registerAs): self
    {
        $this->registerAs = $registerAs;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    public function getUsername(){
        return $this->getEmail()
    }
    public function eraseCredentials(){}
    public function getSalt(){}
    public function getRoles(){
        return ['ROLE_USER'];
    }
}
