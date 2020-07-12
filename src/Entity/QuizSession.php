<?php

namespace App\Entity;

use App\Repository\QuizSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizSessionRepository::class)
 */
class QuizSession
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="quizSessions")
     */
    private $quiz;



    /**
     * @ORM\OneToMany(targetEntity=QuizTry::class, mappedBy="quizSession", cascade={"remove"})
     */
    private $QuizTries;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->QuizTries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }



    /**
     * @return Collection|QuizTry[]
     */
    public function getQuizTries(): Collection
    {
        return $this->QuizTries;
    }

    public function addQuizTry(QuizTry $quizTry): self
    {
        if (!$this->QuizTries->contains($quizTry)) {
            $this->QuizTries[] = $quizTry;
            $quizTry->setQuizSession($this);
        }

        return $this;
    }

    public function removeQuizTry(QuizTry $quizTry): self
    {
        if ($this->QuizTries->contains($quizTry)) {
            $this->QuizTries->removeElement($quizTry);
            // set the owning side to null (unless already changed)
            if ($quizTry->getQuizSession() === $this) {
                $quizTry->setQuizSession(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
