<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student extends User
{
    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $grade;

    /**
     * @ORM\ManyToMany(targetEntity=ClassGroup::class, mappedBy="members")
     */
    private $classGroups;

    public function __construct()
    {
        parent::__construct();
        $this->classGroups = new ArrayCollection();
    }

    /**
     * @return Collection|ClassGroup[]
     */
    public function getClassGroups(): Collection
    {
        return $this->classGroups;
    }

    public function addClassGroup(ClassGroup $classGroup): self
    {
        if (!$this->classGroups->contains($classGroup)) {
            $this->classGroups[] = $classGroup;
            $classGroup->addMember($this);
        }

        return $this;
    }

    public function removeClassGroup(ClassGroup $classGroup): self
    {
        if ($this->classGroups->contains($classGroup)) {
            $this->classGroups->removeElement($classGroup);
            $classGroup->removeMember($this);
        }

        return $this;
    }
}
