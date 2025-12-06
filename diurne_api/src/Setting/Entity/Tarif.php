<?php

namespace App\Setting\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Tarif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $base_price = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column]
    private ?bool $is_confidential = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $base_price_percentage = null;

    #[ORM\Column(nullable: true)]
    private ?int $variation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $vat = null;

    #[ORM\Column(nullable: true)]
    private ?bool $tarif_base = null;

    #[ORM\Column(nullable: true)]
    private ?bool $tarif_pro = null;

    #[ORM\ManyToOne]
    private ?TarifGroup $tarifGroup = null;

    #[ORM\ManyToOne]
    private ?TarifTexture $tarifTexture = null;

    #[ORM\ManyToOne]
    private ?DiscountRule $discountRule = null;

    #[ORM\ManyToOne(targetEntity: QualityTarifTexture::class, inversedBy: 'tarifs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QualityTarifTexture $qualityTarifTexture = null;
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getQualityTarifTexture(): ?QualityTarifTexture
    {
        return $this->qualityTarifTexture;
    }

    public function setQualityTarifTexture(?QualityTarifTexture $qualityTarifTexture): self
    {
        $this->qualityTarifTexture = $qualityTarifTexture;
        return $this;
    }
    public function getBasePrice(): ?string
    {
        return $this->base_price;
    }

    public function setBasePrice(?string $base_price): static
    {
        $this->base_price = $base_price;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function isConfidential(): ?bool
    {
        return $this->is_confidential;
    }

    public function setConfidential(bool $is_confidential): static
    {
        $this->is_confidential = $is_confidential;

        return $this;
    }

    public function getBasePricePercentage(): ?string
    {
        return $this->base_price_percentage;
    }

    public function setBasePricePercentage(?string $base_price_percentage): static
    {
        $this->base_price_percentage = $base_price_percentage;

        return $this;
    }

    public function getVariation(): ?int
    {
        return $this->variation;
    }

    public function setVariation(?int $variation): static
    {
        $this->variation = $variation;

        return $this;
    }

    public function getVat(): ?string
    {
        return $this->vat;
    }

    public function setVat(?string $vat): static
    {
        $this->vat = $vat;

        return $this;
    }

    public function isTarifBase(): ?bool
    {
        return $this->tarif_base;
    }

    public function setTarifBase(?bool $tarif_base): static
    {
        $this->tarif_base = $tarif_base;

        return $this;
    }

    public function isTarifPro(): ?bool
    {
        return $this->tarif_pro;
    }

    public function setTarifPro(?bool $tarif_pro): static
    {
        $this->tarif_pro = $tarif_pro;

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

    public function getTarifTexture(): ?TarifTexture
    {
        return $this->tarifTexture;
    }

    public function setTarifTexture(?TarifTexture $tarifTexture): static
    {
        $this->tarifTexture = $tarifTexture;

        return $this;
    }

    public function getDiscountRule(): ?DiscountRule
    {
        return $this->discountRule;
    }

    public function setDiscountRule(?DiscountRule $discountRule): static
    {
        $this->discountRule = $discountRule;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'base_price' => $this->base_price,
            'label' => $this->label,
            'is_confidential' => $this->is_confidential,
            'base_price_percentage' => $this->base_price_percentage,
            'variation' => $this->variation,
            'vat' => $this->vat,
            'tarif_base' => $this->tarif_base,
            'tarif_pro' => $this->tarif_pro,
            'tarifGroup' => $this->tarifGroup ? $this->tarifGroup->toArray() : null,
            'tarifTexture' => $this->tarifTexture ? $this->tarifTexture->toArray() : null,
            'discountRule' => $this->discountRule ? $this->discountRule->toArray() : null,
        ];
    }
}
