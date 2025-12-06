<?php

namespace App\Contremarque\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CarpetDimension
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CarpetSpecification::class, inversedBy: 'carpetDimensions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CarpetSpecification $carpetSpecification = null;

    #[ORM\ManyToOne]
    private ?Mesurement $mesurement = null;

    /**
     * @var Collection<int, CarpetDimensionValue>
     */
    #[ORM\ManyToMany(targetEntity: CarpetDimensionValue::class, inversedBy: 'carpetDimensions', cascade: ['persist', 'remove'])]
    private Collection $dimensionValues;

    public function __construct()
    {
        $this->dimensionValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarpetSpecification(): ?CarpetSpecification
    {
        return $this->carpetSpecification;
    }

    public function setCarpetSpecification(?CarpetSpecification $carpetSpecification): static
    {
        $this->carpetSpecification = $carpetSpecification;

        return $this;
    }

    public function getMesurement(): ?Mesurement
    {
        return $this->mesurement;
    }

    public function setMesurement(?Mesurement $mesurement): static
    {
        $this->mesurement = $mesurement;

        return $this;
    }

    /**
     * @return Collection<int, CarpetDimensionValue>
     */
    public function getDimensionValues(): Collection
    {
        return $this->dimensionValues;
    }

    public function addDimensionValue(CarpetDimensionValue $dimensionValue): static
    {
        if (!$this->dimensionValues->contains($dimensionValue)) {
            $this->dimensionValues->add($dimensionValue);
        }

        return $this;
    }

    public function removeDimensionValue(CarpetDimensionValue $dimensionValue): static
    {
        $this->dimensionValues->removeElement($dimensionValue);

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'mesurement' => $this->getMesurement() ? $this->getMesurement()->toArray() : null,
            'dimensionValues' => $this->getDimensionValues()->map(fn(CarpetDimensionValue $value) => $value->toArray())->toArray(),
        ];
    }
}
