<?php

namespace App\Entity;

use App\Repository\ClassGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClassGroupRepository::class)
 */
class ClassGroup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="classGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="studentClassGroups")
     */
    private $studentsMembers;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="class", orphanRemoval=true)
     */
    private $questions;

    public function __construct()
    {
        $this->studentsMembers = new ArrayCollection();
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getStudentsMembers(): Collection
    {
        return $this->studentsMembers;
    }

    public function addStudentsMember(User $studentsMember): self
    {
        if (!$this->studentsMembers->contains($studentsMember)) {
            $this->studentsMembers[] = $studentsMember;
        }

        return $this;
    }

    public function removeStudentsMember(User $studentsMember): self
    {
        if ($this->studentsMembers->contains($studentsMember)) {
            $this->studentsMembers->removeElement($studentsMember);
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
            $question->setClass($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getClass() === $this) {
                $question->setClass(null);
            }
        }

        return $this;
    }
}
