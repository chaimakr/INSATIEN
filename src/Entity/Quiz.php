<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=QuizRepository::class)
 */
class Quiz implements JsonSerializable
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

    /**
     * @ORM\OneToMany(targetEntity=QuizSession::class, mappedBy="quiz")
     */
    private $quizSessions;










    public function __construct()
    {

        $this->quizQuestions = new ArrayCollection();
        $this->quizTries = new ArrayCollection();
        $this->quizSessions = new ArrayCollection();
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
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $questions=[];
        forEach( $this->getQuizQuestions() as $question){
            $answers=[];
            foreach ($question->getQuizAnswers() as $answer){
                array_push($answers,$answer->getContent());
            }
            array_push($questions,['question'=>$question->getContent(),'answers'=>$answers]);
        }

        return [
            "title" => $this->getTitle(),
            "class" => $this->getClass()->getTitle(),
            "questions" => $questions
        ];
    }

    /**
     * @return Collection|QuizSession[]
     */
    public function getQuizSessions(): Collection
    {
        return $this->quizSessions;
    }

    public function addQuizSession(QuizSession $quizSession): self
    {
        if (!$this->quizSessions->contains($quizSession)) {
            $this->quizSessions[] = $quizSession;
            $quizSession->setQuiz($this);
        }

        return $this;
    }

    public function removeQuizSession(QuizSession $quizSession): self
    {
        if ($this->quizSessions->contains($quizSession)) {
            $this->quizSessions->removeElement($quizSession);
            // set the owning side to null (unless already changed)
            if ($quizSession->getQuiz() === $this) {
                $quizSession->setQuiz(null);
            }
        }

        return $this;
    }

}
