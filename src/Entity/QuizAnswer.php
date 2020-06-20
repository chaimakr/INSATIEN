<?php

namespace App\Entity;

use App\Repository\QuizAnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizAnswerRepository::class)
 */
class QuizAnswer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=quizQuestion::class, inversedBy="quizAnswers")
     */
    private $quizQuestion;

    /**
     * @ORM\ManyToMany(targetEntity=QuizTry::class, mappedBy="quizAnswers")
     */
    private $quizTries;

    /**
     * @ORM\Column(type="boolean")
     */
    private $valid;

    public function __construct()
    {
        $this->quizTries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getQuizQuestion(): ?quizQuestion
    {
        return $this->quizQuestion;
    }

    public function setQuizQuestion(?quizQuestion $quizQuestion): self
    {
        $this->quizQuestion = $quizQuestion;

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
            $quizTry->addQuizAnswer($this);
        }

        return $this;
    }

    public function removeQuizTry(QuizTry $quizTry): self
    {
        if ($this->quizTries->contains($quizTry)) {
            $this->quizTries->removeElement($quizTry);
            $quizTry->removeQuizAnswer($this);
        }

        return $this;
    }

    public function getValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }
}
