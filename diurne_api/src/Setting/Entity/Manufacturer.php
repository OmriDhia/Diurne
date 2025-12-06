<?php

namespace App\Setting\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Manufacturer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 128)]
    private ?string $company = null;

    #[ORM\Column(length: 1, nullable: true)]
    private ?string $carpetPrefix = null;

    #[ORM\Column(length: 1, nullable: true)]
    private ?string $samplePrefix = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $creditAmount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $complexityBonus = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $oversizeBonus = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $oversizeMohaiBonus = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $multiLevelBonus = null;

    #[ORM\ManyToOne]
    private ?Country $carpetCountry = null;

    #[ORM\ManyToOne]
    private ?Currency $currency = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $specialFormBonus = null;
    /**
     * @var Collection<int, ManufacturerPriceGrid>
     */
    #[ORM\OneToMany(targetEntity: ManufacturerPriceGrid::class, mappedBy: 'manufacturer')]
    private Collection $priceGrids;

    public function __construct()
    {
        $this->priceGrids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getCarpetPrefix(): ?string
    {
        return $this->carpetPrefix;
    }

    public function setCarpetPrefix(?string $carpetPrefix): static
    {
        $this->carpetPrefix = $carpetPrefix;

        return $this;
    }

    public function getSamplePrefix(): ?string
    {
        return $this->samplePrefix;
    }

    public function setSamplePrefix(?string $samplePrefix): static
    {
        $this->samplePrefix = $samplePrefix;

        return $this;
    }

    public function getCreditAmount(): ?string
    {
        return $this->creditAmount;
    }

    public function setCreditAmount(?string $creditAmount): static
    {
        $this->creditAmount = $creditAmount;

        return $this;
    }

    public function getComplexityBonus(): ?string
    {
        return $this->complexityBonus;
    }

    public function setComplexityBonus(?string $complexityBonus): static
    {
        $this->complexityBonus = $complexityBonus;

        return $this;
    }

    public function getOversizeBonus(): ?string
    {
        return $this->oversizeBonus;
    }

    public function setOversizeBonus(?string $oversizeBonus): static
    {
        $this->oversizeBonus = $oversizeBonus;

        return $this;
    }

    public function getOversizeMohaiBonus(): ?string
    {
        return $this->oversizeMohaiBonus;
    }

    public function setOversizeMohaiBonus(?string $oversizeMohaiBonus): static
    {
        $this->oversizeMohaiBonus = $oversizeMohaiBonus;

        return $this;
    }

    public function getMultiLevelBonus(): ?string
    {
        return $this->multiLevelBonus;
    }

    public function setMultiLevelBonus(?string $multiLevelBonus): static
    {
        $this->multiLevelBonus = $multiLevelBonus;

        return $this;
    }

    public function getCarpetCountry(): ?Country
    {
        return $this->carpetCountry;
    }

    public function setCarpetCountry(?Country $carpetCountry): static
    {
        $this->carpetCountry = $carpetCountry;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): static
    {
        $this->currency = $currency;

        return $this;
    }
    /**
     * @return Collection<int, ManufacturerPriceGrid>
     */
    public function getPriceGrids(): Collection
    {
        return $this->priceGrids;
    }

    public function addPriceGrid(ManufacturerPriceGrid $priceGrid): static
    {
        if (!$this->priceGrids->contains($priceGrid)) {
            $this->priceGrids->add($priceGrid);
            $priceGrid->setManufacturer($this);
        }
        return $this;
    }

    public function removePriceGrid(ManufacturerPriceGrid $priceGrid): static
    {
        if ($this->priceGrids->removeElement($priceGrid)) {
            if ($priceGrid->getManufacturer() === $this) {
                $priceGrid->setManufacturer(null);
            }
        }
        return $this;
    }

    public function getSpecialFormBonus(): ?string
    {
        return $this->specialFormBonus;
    }

    public function setSpecialFormBonus(?string $specialFormBonus): static
    {
        $this->specialFormBonus = $specialFormBonus;

        return $this;
    }
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'company' => $this->getCompany(),
            'carpetPrefix' => $this->getCarpetPrefix(),
            'samplePrefix' => $this->getSamplePrefix(),
            'creditAmount' => $this->getCreditAmount(),
            'complexityBonus' => $this->getComplexityBonus(),
            'oversizeBonus' => $this->getOversizeBonus(),
            'oversizeMohaiBonus' => $this->getOversizeMohaiBonus(),
            'multiLevelBonus' => $this->getMultiLevelBonus(),
            'specialFormBonus' => $this->getSpecialFormBonus(),
            'carpetCountry' => $this->getCarpetCountry()?->toArray(),
            'currency' => $this->getCurrency()?->toArray(),
        ];
    }
}
