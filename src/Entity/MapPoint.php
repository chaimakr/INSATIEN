<?php

namespace App\Entity;

use App\Repository\MapPointRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=MapPointRepository::class)
 */
class MapPoint implements JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=covoiturage::class, inversedBy="mapPoints")
     * @ORM\JoinColumn(nullable=false)
     */
    private $covoiturage;

    /**
     * @ORM\Column(type="float")
     */
    private $x;

    /**
     * @ORM\Column(type="float")
     */
    private $y;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCovoiturage(): ?covoiturage
    {
        return $this->covoiturage;
    }

    public function setCovoiturage(?covoiturage $covoiturage): self
    {
        $this->covoiturage = $covoiturage;

        return $this;
    }

    public function getX(): ?float
    {
        return $this->x;
    }

    public function setX(float $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?float
    {
        return $this->y;
    }

    public function setY(float $y): self
    {
        $this->y = $y;

        return $this;
    }




    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'x'=>$this->getX(),
            'y'=>$this->getY(),
            'covoiturageId'=>$this->getCovoiturage()->getId()
        ];
    }
}
