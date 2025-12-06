<?php

declare(strict_types=1);

namespace App\Setting\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'manufacturer_price')]
class ManufacturerPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'manufacturerPrices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ManufacturerPriceGrid $manufacturerPriceGrid = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Material $material = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $price = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $effectiveDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getManufacturerPriceGrid(): ?ManufacturerPriceGrid
    {
        return $this->manufacturerPriceGrid;
    }

    public function setManufacturerPriceGrid(?ManufacturerPriceGrid $manufacturerPriceGrid): static
    {
        $this->manufacturerPriceGrid = $manufacturerPriceGrid;

        return $this;
    }

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(?Material $material): static
    {
        $this->material = $material;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }


    public function setPrice(float|string $price): static
    {
        $this->price = (string) $price;


        return $this;
    }

    public function getEffectiveDate(): ?\DateTimeInterface
    {
        return $this->effectiveDate;
    }

    public function setEffectiveDate(\DateTimeInterface $effectiveDate): static
    {
        $this->effectiveDate = $effectiveDate;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'material' => [
                'id' => $this->material?->getId(),
                'reference' => $this->material?->getReference(),
            ],
            'price' => $this->price,
            'effectiveDate' => $this->effectiveDate?->format('Y-m-d'),
        ];
    }
}
