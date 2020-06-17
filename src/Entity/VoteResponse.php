<?php

namespace App\Entity;

use App\Repository\VoteResponseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoteResponseRepository::class)
 */
class VoteResponse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="voteResponses")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=response::class, inversedBy="voteResponses")
     */
    private $response;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getResponse(): ?response
    {
        return $this->response;
    }

    public function setResponse(?response $response): self
    {
        $this->response = $response;

        return $this;
    }
}
