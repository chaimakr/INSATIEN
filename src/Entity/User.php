<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @Assert\NotBlank
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex(
     * pattern="#@insat.u-carthage.tn#",
     * message="use your insat.u-carthage mail ya haj")
     */
    private $email;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     *  @Assert\Length(min="8",minMessage="your password should contain at least 8 characters !")
     */


    private $password;
    
    /**
     * @Assert\NotBlank
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

    /**
     * @ORM\OneToMany(targetEntity=Covoiturage::class, mappedBy="owner", orphanRemoval=true)
     */
    private $covoiturages;

    /**
     * @ORM\OneToMany(targetEntity=ClassGroup::class, mappedBy="owner")
     */
    private $TeacherClassGroups;

    /**
     * @ORM\ManyToMany(targetEntity=ClassGroup::class, mappedBy="studentsMembers")
     */
    private $studentClassGroups;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="owner")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=Response::class, mappedBy="owner", orphanRemoval=true)
     */
    private $responses;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pdpPath;

    public function __construct()
    {
        $this->covoiturages = new ArrayCollection();
        $this->TeacherclassGroups = new ArrayCollection();
        $this->studentClassGroups = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->responses = new ArrayCollection();
        $this->TeacherClassGroups = new ArrayCollection();
    }


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
        return $this->email;
    }
    public function eraseCredentials(){}
    public function getSalt(){}
    public function getRoles(){
        if($this->confirmationCode=='confirmed'){
            if($this->getRegisterAs()=='student') return  ['ROLE_STUDENT'];
            elseif ($this->getRegisterAs()=="teacher") return  ['ROLE_TEACHER'];
    }

        else
            return['ROLE_UNCONFIRMED'];
    }

    /**
     * @return Collection|Covoiturage[]
     */
    public function getCovoiturages(): Collection
    {
        return $this->covoiturages;
    }

    public function addCovoiturage(Covoiturage $covoiturage): self
    {
        if (!$this->covoiturages->contains($covoiturage)) {
            $this->covoiturages[] = $covoiturage;
            $covoiturage->setOwner($this);
        }

        return $this;
    }

    public function removeCovoiturage(Covoiturage $covoiturage): self
    {
        if ($this->covoiturages->contains($covoiturage)) {
            $this->covoiturages->removeElement($covoiturage);
            // set the owning side to null (unless already changed)
            if ($covoiturage->getOwner() === $this) {
                $covoiturage->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ClassGroup[]
     */
    public function getTeacherClassGroups(): Collection
    {
        return $this->TeacherClassGroups;
    }

    public function addTeacherClassGroup(ClassGroup $classGroup): self
    {
        if (!$this->TeacherClassGroups->contains($classGroup)) {
            $this->TeacherClassGroups[] = $classGroup;
            $classGroup->setOwner($this);
        }

        return $this;
    }

    public function removeTeacherClassGroup(ClassGroup $classGroup): self
    {
        if ($this->TeacherClassGroups->contains($classGroup)) {
            $this->TeacherClassGroups->removeElement($classGroup);
            // set the owning side to null (unless already changed)
            if ($classGroup->getOwner() === $this) {
                $classGroup->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ClassGroup[]
     */
    public function getStudentClassGroups(): Collection
    {
        return $this->studentClassGroups;
    }

    public function addStudentClassGroup(ClassGroup $studentClassGroup): self
    {
        if (!$this->studentClassGroups->contains($studentClassGroup)) {
            $this->studentClassGroups[] = $studentClassGroup;
            $studentClassGroup->addStudentsMember($this);
        }

        return $this;
    }

    public function removeStudentClassGroup(ClassGroup $studentClassGroup): self
    {
        if ($this->studentClassGroups->contains($studentClassGroup)) {
            $this->studentClassGroups->removeElement($studentClassGroup);
            $studentClassGroup->removeStudentsMember($this);
        }

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setOwner($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getOwner() === $this) {
                $question->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Response[]
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(Response $response): self
    {
        if (!$this->responses->contains($response)) {
            $this->responses[] = $response;
            $response->setOwner($this);
        }

        return $this;
    }

    public function removeResponse(Response $response): self
    {
        if ($this->responses->contains($response)) {
            $this->responses->removeElement($response);
            // set the owning side to null (unless already changed)
            if ($response->getOwner() === $this) {
                $response->setOwner(null);
            }
        }

        return $this;
    }

    public function getPdpPath(): ?string
    {
        return $this->pdpPath;
    }

    public function setPdpPath(string $pdpPath): self
    {
        $this->pdpPath = $pdpPath;

        return $this;
    }
}
