<?php

declare(strict_types=1);

namespace App\MobileAppApi\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity]
class RN
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $rnNumber;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: Workshop::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Workshop $workshop = null;

    #[ORM\OneToMany(mappedBy: 'rn', targetEntity: StockExit::class)]
    private Collection $stockExits;

    #[ORM\OneToMany(mappedBy: 'rn', targetEntity: StockEntry::class)]
    private Collection $stockEntries;

    #[ORM\OneToMany(mappedBy: 'rn', targetEntity: ProgressReport::class)]
    private Collection $progressReports;


    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->stockExits = new ArrayCollection();
        $this->stockEntries = new ArrayCollection();
        $this->progressReports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRnNumber(): string
    {
        return $this->rnNumber;
    }

    public function setRnNumber(string $rnNumber): void
    {
        $this->rnNumber = $rnNumber;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getWorkshop(): ?Workshop
    {
        return $this->workshop;
    }

    public function setWorkshop(?Workshop $workshop): void
    {
        $this->workshop = $workshop;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'rnNumber' => $this->rnNumber,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'workshop' => $this->workshop?->toArray(),
        ];
    }
}
