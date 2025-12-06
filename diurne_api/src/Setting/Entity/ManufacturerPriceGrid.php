<?php

declare(strict_types=1);

namespace App\Setting\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Setting\Entity\TarifGroup;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'manufacturer_price_grid')]
#[ORM\UniqueConstraint(name: 'unique_manufacturer_quality_tarif_group', columns: ['manufacturer_id', 'quality_id', 'tarif_group_id'])]
class ManufacturerPriceGrid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Manufacturer::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Manufacturer $manufacturer = null;

    #[ORM\ManyToOne(targetEntity: Quality::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quality $quality = null;

    #[ORM\ManyToOne(targetEntity: TarifGroup::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?TarifGroup $tarifGroup = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $tariffGrid = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $knots = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $special = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $standardVelours = null;

    /**
     * @var Collection<int, ManufacturerPrice>
     */
    #[ORM\OneToMany(mappedBy: 'manufacturerPriceGrid', targetEntity: ManufacturerPrice::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $manufacturerPrices;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private ?bool $isActive = true;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->manufacturerPrices = new ArrayCollection();
    }

    // -------------------------------
    // LIFECYCLE CALLBACKS
    // -------------------------------
    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    // -------------------------------
    // GETTERS & SETTERS
    // -------------------------------
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?Manufacturer $manufacturer): static
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }

    public function getQuality(): ?Quality
    {
        return $this->quality;
    }

    public function setQuality(?Quality $quality): static
    {
        $this->quality = $quality;
        return $this;
    }

    public function getTarifGroup(): ?TarifGroup
    {
        return $this->tarifGroup;
    }

    public function setTarifGroup(?TarifGroup $tarifGroup): static
    {
        $this->tarifGroup = $tarifGroup;
        return $this;
    }

    public function getTariffGrid(): ?string
    {
        return $this->tariffGrid;
    }

    public function setTariffGrid(?string $tariffGrid): static
    {
        $this->tariffGrid = $tariffGrid;
        return $this;
    }

    public function getKnots(): ?int
    {
        return $this->knots;
    }

    public function setKnots(?int $knots): static
    {
        $this->knots = $knots;
        return $this;
    }

    public function getSpecial(): ?string
    {
        return $this->special;
    }

    public function setSpecial(?string $special): static
    {
        $this->special = $special;
        return $this;
    }

    public function getStandardVelours(): ?string
    {
        return $this->standardVelours;
    }

    public function setStandardVelours(?string $standardVelours): static
    {
        $this->standardVelours = $standardVelours;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'manufacturer' => [
                'id' => $this->manufacturer?->getId(),
                'name' => $this->manufacturer?->getName(),
                'company' => $this->manufacturer?->getCompany(),
            ],
            'quality' => [
                'id' => $this->quality?->getId(),
                'name' => $this->quality?->getName(),
                'weight' => $this->quality?->getWeight(),
            ],
            'tarifGroup' => $this->tarifGroup?->toArray(),
            'tariffGrid' => $this->tariffGrid,
            'knots' => $this->knots,
            'special' => $this->special,
            'standardVelours' => $this->standardVelours,
            'prices' => $this->manufacturerPrices
                ->map(fn (ManufacturerPrice $manufacturerPrice) => $manufacturerPrice->toArray())
                ->toArray(),
            'isActive' => $this->isActive,
            'createdAt' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @return Collection<int, ManufacturerPrice>
     */
    public function getManufacturerPrices(): Collection
    {
        return $this->manufacturerPrices;
    }

    public function addManufacturerPrice(ManufacturerPrice $manufacturerPrice): static
    {
        if (!$this->manufacturerPrices->contains($manufacturerPrice)) {
            $this->manufacturerPrices->add($manufacturerPrice);
            $manufacturerPrice->setManufacturerPriceGrid($this);
        }

        return $this;
    }

    public function removeManufacturerPrice(ManufacturerPrice $manufacturerPrice): static
    {
        if ($this->manufacturerPrices->removeElement($manufacturerPrice)) {
            if ($manufacturerPrice->getManufacturerPriceGrid() === $this) {
                $manufacturerPrice->setManufacturerPriceGrid(null);
            }
        }

        return $this;
    }
}
