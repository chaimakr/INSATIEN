<?php

namespace App\Entity;

use App\Repository\CovoiturageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CovoiturageRepository::class)
 */
class Covoiturage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="covoiturages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=MapPoint::class, mappedBy="covoiturage", orphanRemoval=true)
     */
    private $mapPoints;

    public function __construct()
    {
        $this->mapPoints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|MapPoint[]
     */
    public function getMapPoints(): Collection
    {
        return $this->mapPoints;
    }

    public function addMapPoint(MapPoint $mapPoint): self
    {
        if (!$this->mapPoints->contains($mapPoint)) {
            $this->mapPoints[] = $mapPoint;
            $mapPoint->setCovoiturage($this);
        }

        return $this;
    }

    public function removeMapPoint(MapPoint $mapPoint): self
    {
        if ($this->mapPoints->contains($mapPoint)) {
            $this->mapPoints->removeElement($mapPoint);
            // set the owning side to null (unless already changed)
            if ($mapPoint->getCovoiturage() === $this) {
                $mapPoint->setCovoiturage(null);
            }
        }

        return $this;
    }
}