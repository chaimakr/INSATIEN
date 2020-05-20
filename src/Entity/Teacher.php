<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeacherRepository::class)
 */
class Teacher extends User
{
    /**
     * @ORM\OneToMany(targetEntity=ClassGroup::class, mappedBy="owner", orphanRemoval=true)
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
            $classGroup->setOwner($this);
        }

        return $this;
    }

    public function removeClassGroup(ClassGroup $classGroup): self
    {
        if ($this->classGroups->contains($classGroup)) {
            $this->classGroups->removeElement($classGroup);
            // set the owning side to null (unless already changed)
            if ($classGroup->getOwner() === $this) {
                $classGroup->setOwner(null);
            }
        }

        return $this;
    }
}
