<?php

namespace App\Entity;

use App\Repository\QuizTryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizTryRepository::class)
 */
class QuizTry
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="quizTries")
     */
    private $student;

    /**
     * @ORM\ManyToMany(targetEntity=QuizAnswer::class, inversedBy="quizTries")
     */
    private $quizAnswers;



    /**
     * @ORM\ManyToOne(targetEntity=QuizSession::class, inversedBy="QuizTries")
     */
    private $quizSession;

    public function __construct()
    {
        $this->quizAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $student): self
    {
        $this->student = $student;

        return $this;
    }

    /**
     * @return Collection|QuizAnswer[]
     */
    public function getQuizAnswers(): Collection
    {
        return $this->quizAnswers;
    }

    public function addQuizAnswer(QuizAnswer $quizAnswer): self
    {
        if (!$this->quizAnswers->contains($quizAnswer)) {
            $this->quizAnswers[] = $quizAnswer;
        }

        return $this;
    }

    public function removeQuizAnswer(QuizAnswer $quizAnswer): self
    {
        if ($this->quizAnswers->contains($quizAnswer)) {
            $this->quizAnswers->removeElement($quizAnswer);
        }

        return $this;
    }



    public function getQuizSession(): ?QuizSession
    {
        return $this->quizSession;
    }

    public function setQuizSession(?QuizSession $quizSession): self
    {
        $this->quizSession = $quizSession;

        return $this;
    }
}
