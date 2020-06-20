<?php

namespace App\Entity;

use App\Repository\ResponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResponseRepository::class)
 */
class Response
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $evaluation;
     
    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="responses")
     * @ORM\JoinColumn(nullable=true)
     */
    private $Question;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="responses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Response::class, inversedBy="responses")
     * @ORM\JoinColumn(nullable=true)
     */
    private $main;

    /**
     * @ORM\OneToMany(targetEntity=Response::class, mappedBy="main", orphanRemoval=true)
     */
    private $responses;

    /**
     * @ORM\OneToMany(targetEntity=VoteResponse::class, mappedBy="response")
     */
    private $voteResponses;

    public function __construct()
    {
        $this->responses = new ArrayCollection();
        $this->voteResponses = new ArrayCollection();
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

    public function getEvaluation(): ?int
    {
        return $this->evaluation;
    }

    public function setEvaluation(?int $evaluation): self
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->Question;
    }

    public function setQuestion(?Question $Quesion): self
    {
        $this->Question = $Quesion;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMain(): ?Responses
    {
        return $this->main;
    }

    public function setMain(?Response $mainResponse): self
    {
        $this->main = $mainResponse;

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
            $response->setMainResponse($this);
        }

        return $this;
    }

    public function removeResponse(Response $response): self
    {
        if ($this->responses->contains($response)) {
            $this->responses->removeElement($response);
            // set the owning side to null (unless already changed)
            if ($response->getMainResponse() === $this) {
                $response->setMainResponse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|VoteResponse[]
     */
    public function getVoteResponses(): Collection
    {
        return $this->voteResponses;
    }

    public function addVoteResponse(VoteResponse $voteResponse): self
    {
        if (!$this->voteResponses->contains($voteResponse)) {
            $this->voteResponses[] = $voteResponse;
            $voteResponse->setResponse($this);
        }

        return $this;
    }

    public function removeVoteResponse(VoteResponse $voteResponse): self
    {
        if ($this->voteResponses->contains($voteResponse)) {
            $this->voteResponses->removeElement($voteResponse);
            // set the owning side to null (unless already changed)
            if ($voteResponse->getResponse() === $this) {
                $voteResponse->setResponse(null);
            }
        }

        return $this;
    }
}
