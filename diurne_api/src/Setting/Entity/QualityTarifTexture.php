<?php

namespace App\Setting\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class QualityTarifTexture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Quality::class, inversedBy: 'qualityTarifTextures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quality $quality = null;

    #[ORM\ManyToOne]
    private ?TarifTexture $tarifTexture = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $tarif_laine = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $tarif_laine_c = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $tarif_soie = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $tarif_soie_c = null;

    /**
     * @var Collection<int, Tarif>
     */
    #[ORM\OneToMany(targetEntity: Tarif::class, mappedBy: 'qualityTarifTexture')]
    private Collection $tarifs;

    /**
     * @var Collection<int, MaterialPrice>
     */
    #[ORM\OneToMany(targetEntity: MaterialPrice::class, mappedBy: 'qualityTarifTexture')]
    private Collection $materialPrices;

    public function __construct()
    {
        $this->tarifs = new ArrayCollection();
        $this->materialPrices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTarifTexture(): ?TarifTexture
    {
        return $this->tarifTexture;
    }

    public function setTarifTexture(?TarifTexture $tarifTexture): static
    {
        $this->tarifTexture = $tarifTexture;

        return $this;
    }

    public function getTarifLaine(): ?string
    {
        return $this->tarif_laine;
    }

    public function setTarifLaine(?string $tarif_laine): static
    {
        $this->tarif_laine = $tarif_laine;

        return $this;
    }

    public function getTarifLaineC(): ?string
    {
        return $this->tarif_laine_c;
    }

    public function setTarifLaineC(?string $tarif_laine_c): static
    {
        $this->tarif_laine_c = $tarif_laine_c;

        return $this;
    }

    public function getTarifSoie(): ?string
    {
        return $this->tarif_soie;
    }

    public function setTarifSoie(?string $tarif_soie): static
    {
        $this->tarif_soie = $tarif_soie;

        return $this;
    }

    public function getTarifSoieC(): ?string
    {
        return $this->tarif_soie_c;
    }

    public function setTarifSoieC(?string $tarif_soie_c): static
    {
        $this->tarif_soie_c = $tarif_soie_c;

        return $this;
    }

    /**
     * @return Collection<int, Tarif>
     */
    public function getTarifs(): Collection
    {
        return $this->tarifs;
    }

    public function addTarif(Tarif $tarif): static
    {
        if (!$this->tarifs->contains($tarif)) {
            $this->tarifs->add($tarif);
            $tarif->setQualityTarifTexture($this);
        }

        return $this;
    }

    public function removeTarif(Tarif $tarif): static
    {
        if ($this->tarifs->removeElement($tarif)) {
            // set the owning side to null (unless already changed)
            if ($tarif->getQualityTarifTexture() === $this) {
                $tarif->setQualityTarifTexture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MaterialPrice>
     */
    public function getMaterialPrices(): Collection
    {
        return $this->materialPrices;
    }

    public function addMaterialPrice(MaterialPrice $materialPrice): static
    {
        if (!$this->materialPrices->contains($materialPrice)) {
            $this->materialPrices->add($materialPrice);
            $materialPrice->setQualityTarifTexture($this);
        }

        return $this;
    }

    public function removeMaterialPrice(MaterialPrice $materialPrice): static
    {
        if ($this->materialPrices->removeElement($materialPrice)) {
            // set the owning side to null (unless already changed)
            if ($materialPrice->getQualityTarifTexture() === $this) {
                $materialPrice->setQualityTarifTexture(null);
            }
        }

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'quality' => $this->quality?->toArray(),
            'tarifTexture' => $this->tarifTexture?->toArray(),
            'tarif_laine' => $this->tarif_laine,
            'tarif_laine_c' => $this->tarif_laine_c,
            'tarif_soie' => $this->tarif_soie,
            'tarif_soie_c' => $this->tarif_soie_c,
        ];
    }
}
