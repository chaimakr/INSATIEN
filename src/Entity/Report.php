<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReportRepository::class)
 */
class Report
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reportCause;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date;

    /**
     * @ORM\Column(type="text")
     */
    private $Details;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="reports")
     */
    private $reportedBy;

    /**
     * @ORM\Column(type="object")
     */
    private $entityReported;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReportCause(): ?string
    {
        return $this->reportCause;
    }

    public function setReportCause(string $reportCause): self
    {
        $this->reportCause = $reportCause;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->Details;
    }

    public function setDetails(string $Details): self
    {
        $this->Details = $Details;

        return $this;
    }

    public function getReportedBy(): ?user
    {
        return $this->reportedBy;
    }

    public function setReportedBy(?user $reportedBy): self
    {
        $this->reportedBy = $reportedBy;

        return $this;
    }

    public function getEntityReported()
    {
        return $this->entityReported;
    }

    public function setEntityReported($entityReported): self
    {
        $this->entityReported = $entityReported;

        return $this;
    }
}
