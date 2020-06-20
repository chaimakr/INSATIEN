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
     * @ORM\ManyToOne(targetEntity=USer::class, inversedBy="quizTries")
     */
    private $student;

    /**
     * @ORM\ManyToMany(targetEntity=quizAnswer::class, inversedBy="quizTries")
     */
    private $quizAnswers;

    public function __construct()
    {
        $this->quizAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?USer
    {
        return $this->student;
    }

    public function setStudent(?USer $student): self
    {
        $this->student = $student;

        return $this;
    }

    /**
     * @return Collection|quizAnswer[]
     */
    public function getQuizAnswers(): Collection
    {
        return $this->quizAnswers;
    }

    public function addQuizAnswer(quizAnswer $quizAnswer): self
    {
        if (!$this->quizAnswers->contains($quizAnswer)) {
            $this->quizAnswers[] = $quizAnswer;
        }

        return $this;
    }

    public function removeQuizAnswer(quizAnswer $quizAnswer): self
    {
        if ($this->quizAnswers->contains($quizAnswer)) {
            $this->quizAnswers->removeElement($quizAnswer);
        }

        return $this;
    }
}
