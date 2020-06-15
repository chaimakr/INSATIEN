<?php

namespace App\Entity;

use App\Repository\RequestFromTeacherRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RequestFromTeacherRepository::class)
 */
class RequestFromTeacher
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="requestFromTeachers")
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity=classGroup::class, inversedBy="requestFromTeachers")
     */
    private $classGroup;

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

    public function getClassGroup(): ?classGroup
    {
        return $this->classGroup;
    }

    public function setClassGroup(?classGroup $classGroup): self
    {
        $this->classGroup = $classGroup;

        return $this;
    }
}
