<?php

namespace App\Entity;

use App\Repository\ClassGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClassGroupRepository::class)
 */
class ClassGroup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Teacher::class, inversedBy="classGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity=Student::class, inversedBy="classGroups")
     */
    private $members;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="classGroup", orphanRemoval=true)
     */
    private $content;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->content = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOwner(): ?Teacher
    {
        return $this->owner;
    }

    public function setOwner(?Teacher $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Student $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
        }

        return $this;
    }

    public function removeMember(Student $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
        }

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getContent(): Collection
    {
        return $this->content;
    }

    public function addContent(Question $content): self
    {
        if (!$this->content->contains($content)) {
            $this->content[] = $content;
            $content->setClassGroup($this);
        }

        return $this;
    }

    public function removeContent(Question $content): self
    {
        if ($this->content->contains($content)) {
            $this->content->removeElement($content);
            // set the owning side to null (unless already changed)
            if ($content->getClassGroup() === $this) {
                $content->setClassGroup(null);
            }
        }

        return $this;
    }
}
