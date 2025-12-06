<?php

namespace App\Contremarque\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CarpetPriceSimulator
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CarpetPriceBase::class, cascade: ['persist', 'remove'], inversedBy: 'priceSimulators')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?CarpetPriceBase $basePrice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $unitPriceHt = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $unit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBasePrice(): ?CarpetPriceBase
    {
        return $this->basePrice;
    }

    public function setBasePrice(?CarpetPriceBase $basePrice): static
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    public function getUnitPriceHt(): ?string
    {
        return $this->unitPriceHt;
    }

    public function setUnitPriceHt(?string $unitPriceHt): static
    {
        $this->unitPriceHt = $unitPriceHt;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }
}
