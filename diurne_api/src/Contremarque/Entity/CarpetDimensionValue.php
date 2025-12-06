<?php

namespace App\Contremarque\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CarpetDimensionValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?UnitOfMeasurement $unit = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $value = null;

    /**
     * @var Collection<int, CarpetDimension>
     */
    #[ORM\ManyToMany(targetEntity: CarpetDimension::class, mappedBy: 'dimensionValues')]
    private Collection $carpetDimensions;

    public function __construct()
    {
        $this->carpetDimensions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUnit(): ?UnitOfMeasurement
    {
        return $this->unit;
    }

    public function setUnit(?UnitOfMeasurement $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection<int, CarpetDimension>
     */
    public function getCarpetDimensions(): Collection
    {
        return $this->carpetDimensions;
    }

    public function addCarpetDimension(CarpetDimension $carpetDimension): static
    {
        if (!$this->carpetDimensions->contains($carpetDimension)) {
            $this->carpetDimensions->add($carpetDimension);
            $carpetDimension->addDimensionValue($this);
        }

        return $this;
    }

    public function removeCarpetDimension(CarpetDimension $carpetDimension): static
    {
        if ($this->carpetDimensions->removeElement($carpetDimension)) {
            $carpetDimension->removeDimensionValue($this);
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'unit' => $this->getUnit() ? $this->getUnit()->toArray() : null,
            'value' => $this->getValue(),
        ];
    }
}
