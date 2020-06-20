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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=RequestFromStudent::class, mappedBy="classGroup")
     */
    private $requestFromStudents;

    /**
     * @ORM\OneToMany(targetEntity=RequestFromTeacher::class, mappedBy="classGroup")
     */
    private $requestFromTeachers;

    /**
     * @ORM\OneToMany(targetEntity=Quiz::class, mappedBy="class")
     */
    private $quizzes;


    public function __construct()
    {
        $this->studentsMembers = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->requestFromStudents = new ArrayCollection();
        $this->requestFromTeachers = new ArrayCollection();
        $this->quizzes = new ArrayCollection();

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|RequestFromStudent[]
     */
    public function getRequestFromStudents(): Collection
    {
        return $this->requestFromStudents;
    }

    public function addRequestFromStudent(RequestFromStudent $requestFromStudent): self
    {
        if (!$this->requestFromStudents->contains($requestFromStudent)) {
            $this->requestFromStudents[] = $requestFromStudent;
            $requestFromStudent->setClassGroup($this);
        }

        return $this;
    }

    public function removeRequestFromStudent(RequestFromStudent $requestFromStudent): self
    {
        if ($this->requestFromStudents->contains($requestFromStudent)) {
            $this->requestFromStudents->removeElement($requestFromStudent);
            // set the owning side to null (unless already changed)
            if ($requestFromStudent->getClassGroup() === $this) {
                $requestFromStudent->setClassGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RequestFromTeacher[]
     */
    public function getRequestFromTeachers(): Collection
    {
        return $this->requestFromTeachers;
    }

    public function addRequestFromTeacher(RequestFromTeacher $requestFromTeacher): self
    {
        if (!$this->requestFromTeachers->contains($requestFromTeacher)) {
            $this->requestFromTeachers[] = $requestFromTeacher;
            $requestFromTeacher->setClassGroup($this);
        }

        return $this;
    }

    public function removeRequestFromTeacher(RequestFromTeacher $requestFromTeacher): self
    {
        if ($this->requestFromTeachers->contains($requestFromTeacher)) {
            $this->requestFromTeachers->removeElement($requestFromTeacher);
            // set the owning side to null (unless already changed)
            if ($requestFromTeacher->getClassGroup() === $this) {
                $requestFromTeacher->setClassGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Quiz[]
     */
    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function addQuiz(Quiz $quiz): self
    {
        if (!$this->quizzes->contains($quiz)) {
            $this->quizzes[] = $quiz;
            $quiz->setClass($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): self
    {
        if ($this->quizzes->contains($quiz)) {
            $this->quizzes->removeElement($quiz);
            // set the owning side to null (unless already changed)
            if ($quiz->getClass() === $this) {
                $quiz->setClass(null);
            }
        }

        return $this;
    }


}
