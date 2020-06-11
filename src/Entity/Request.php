<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RequestRepository::class)
 */
class Request
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="requests")
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity=ClassGroup::class, inversedBy="requests")
     */
    private $class;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?user
    {
        return $this->student;
    }

    public function setStudent(?user $student): self
    {
        $this->student = $student;

        return $this;
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
}
