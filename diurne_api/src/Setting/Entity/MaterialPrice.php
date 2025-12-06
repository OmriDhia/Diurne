<?php

namespace App\Setting\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class MaterialPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $publicPrice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $bigProjectPrice = null;

    #[ORM\ManyToOne(inversedBy: 'materialPrices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Material $material = null;

    #[ORM\ManyToOne(inversedBy: 'materialPrices')]
    private ?QualityTarifTexture $qualityTarifTexture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublicPrice(): ?string
    {
        return $this->publicPrice;
    }

    public function setPublicPrice(?string $publicPrice): static
    {
        $this->publicPrice = $publicPrice;

        return $this;
    }

    public function getBigProjectPrice(): ?string
    {
        return $this->bigProjectPrice;
    }

    public function setBigProjectPrice(?string $bigProjectPrice): static
    {
        $this->bigProjectPrice = $bigProjectPrice;

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

    /**
     * @return (int|mixed|null|string)[]
     *
     * @psalm-return array{id: int|null, publicPrice: null|string, bigProjectPrice: null|string, material: mixed}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'publicPrice' => $this->publicPrice,
            'bigProjectPrice' => $this->bigProjectPrice,
            'material' => $this->material->toArray(),
            'qualityTarifTexture' => $this->qualityTarifTexture?->toArray(),
        ];
    }

    public function getQualityTarifTexture(): ?QualityTarifTexture
    {
        return $this->qualityTarifTexture;
    }

    public function setQualityTarifTexture(?QualityTarifTexture $qualityTarifTexture): static
    {
        $this->qualityTarifTexture = $qualityTarifTexture;

        return $this;
    }
}
