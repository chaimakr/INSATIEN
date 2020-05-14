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

    /**
     * @ORM\Column(type="string",length=65532, nullable=true)
     */
    private $moreDetails;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $departurePoint;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $arrivalPoint;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $departureTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $returnTime;



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

    public function getMoreDetails(): ?string
    {
        return $this->moreDetails;
    }

    public function setMoreDetails(?string $moreDetails): self
    {
        $this->moreDetails = $moreDetails;

        return $this;
    }

    public function getDeparturePoint(): ?string
    {
        return $this->departurePoint;
    }

    public function setDeparturePoint(string $departurePoint): self
    {
        $this->departurePoint = $departurePoint;

        return $this;
    }

    public function getArrivalPoint(): ?string
    {
        return $this->arrivalPoint;
    }

    public function setArrivalPoint(string $arrivalPoint): self
    {
        $this->arrivalPoint = $arrivalPoint;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDepartureTime(): ?int
    {
        return $this->departureTime;
    }

    public function setDepartureTime(?int $departureTime): self
    {
        $this->departureTime = $departureTime;

        return $this;
    }

    public function getReturnTime(): ?int
    {
        return $this->returnTime;
    }

    public function setReturnTime(?int $returnTime): self
    {
        $this->returnTime = $returnTime;

        return $this;
    }


}
