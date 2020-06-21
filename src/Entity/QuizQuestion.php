<?php

namespace App\Entity;

use App\Repository\QuizQuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=QuizQuestionRepository::class)
 */
class QuizQuestion implements JsonSerializable
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
     * @ORM\OneToMany(targetEntity=QuizAnswer::class, mappedBy="quizQuestion", cascade={"remove"})
     */
    private $quizAnswers;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="quizQuestions")
     */
    private $quiz;

    public function __construct()
    {
        $this->quizAnswers = new ArrayCollection();
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
            $quizAnswer->setQuizQuestion($this);
        }

        return $this;
    }

    public function removeQuizAnswer(QuizAnswer $quizAnswer): self
    {
        if ($this->quizAnswers->contains($quizAnswer)) {
            $this->quizAnswers->removeElement($quizAnswer);
            // set the owning side to null (unless already changed)
            if ($quizAnswer->getQuizQuestion() === $this) {
                $quizAnswer->setQuizQuestion(null);
            }
        }

        return $this;
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
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'content' => $this->getContent()
        ];
    }
}
