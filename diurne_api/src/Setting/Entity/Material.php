<?php

namespace App\Setting\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Material
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $reference = null;

    /**
     * @var Collection<int, MaterialLang>
     */
    #[ORM\OneToMany(targetEntity: MaterialLang::class, mappedBy: 'material')]
    private Collection $materialLangs;

    /**
     * @var Collection<int, MaterialPrice>
     */
    #[ORM\OneToMany(targetEntity: MaterialPrice::class, mappedBy: 'material', orphanRemoval: true)]
    private Collection $materialPrices;

    public function __construct()
    {
        $this->materialLangs = new ArrayCollection();
        $this->materialPrices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection<int, MaterialLang>
     */
    public function getMaterialLangs(): Collection
    {
        return $this->materialLangs;
    }

    public function addMaterialLang(MaterialLang $materialLang): static
    {
        if (!$this->materialLangs->contains($materialLang)) {
            $this->materialLangs->add($materialLang);
            $materialLang->setMaterial($this);
        }

        return $this;
    }

    public function removeMaterialLang(MaterialLang $materialLang): static
    {
        if ($this->materialLangs->removeElement($materialLang)) {
            // set the owning side to null (unless already changed)
            if ($materialLang->getMaterial() === $this) {
                $materialLang->setMaterial(null);
            }
        }

        return $this;
    }

    /**
     * @return (array|int|null|string)[]
     *
     * @psalm-return array{id: int|null, reference: null|string, materialLangs: array<int, mixed>}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'reference' => $this->getReference(),
            'materialLangs' => $this->getMaterialLangs()->map(fn (MaterialLang $materialLang) => $materialLang->toArray())->toArray(),
        ];
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
            $materialPrice->setMaterial($this);
        }

        return $this;
    }

    public function removeMaterialPrice(MaterialPrice $materialPrice): static
    {
        if ($this->materialPrices->removeElement($materialPrice)) {
            // set the owning side to null (unless already changed)
            if ($materialPrice->getMaterial() === $this) {
                $materialPrice->setMaterial(null);
            }
        }

        return $this;
    }
}
