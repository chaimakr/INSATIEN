<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizRepository::class)
 */
class Quiz
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ClassGroup::class, inversedBy="quizzes")
     */
    private $class;


    /**
     * @ORM\OneToMany(targetEntity=QuizQuestion::class, mappedBy="quiz" , cascade={"remove"})
     */
    private $quizQuestions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=QuizTry::class, mappedBy="quiz")
     */
    private $quizTries;



    public function __construct()
    {

        $this->quizQuestions = new ArrayCollection();
        $this->quizTries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClass(): ?ClassGroup
    {
        return $this->class;
    }

    public function setClass(?ClassGroup $class): self
    {
        $this->class = $class;

        return $this;
    }



    /**
     * @return Collection|QuizQuestion[]
     */
    public function getQuizQuestions(): Collection
    {
        return $this->quizQuestions;
    }

    public function addQuizQuestion(QuizQuestion $quizQuestion): self
    {
        if (!$this->quizQuestions->contains($quizQuestion)) {
            $this->quizQuestions[] = $quizQuestion;
            $quizQuestion->setQuiz($this);
        }

        return $this;
    }

    public function removeQuizQuestion(QuizQuestion $quizQuestion): self
    {
        if ($this->quizQuestions->contains($quizQuestion)) {
            $this->quizQuestions->removeElement($quizQuestion);
            // set the owning side to null (unless already changed)
            if ($quizQuestion->getQuiz() === $this) {
                $quizQuestion->setQuiz(null);
            }
        }

        return $this;
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

    /**
     * @return Collection|QuizTry[]
     */
    public function getQuizTries(): Collection
    {
        return $this->quizTries;
    }

    public function addQuizTry(QuizTry $quizTry): self
    {
        if (!$this->quizTries->contains($quizTry)) {
            $this->quizTries[] = $quizTry;
            $quizTry->setQuiz($this);
        }

        return $this;
    }

    public function removeQuizTry(QuizTry $quizTry): self
    {
        if ($this->quizTries->contains($quizTry)) {
            $this->quizTries->removeElement($quizTry);
            // set the owning side to null (unless already changed)
            if ($quizTry->getQuiz() === $this) {
                $quizTry->setQuiz(null);
            }
        }

        return $this;
    }


}
