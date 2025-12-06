<?php

namespace App\Setting\Entity;

use App\Contremarque\Entity\Sample;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Setting\Entity\CarpetCollection;
use App\Setting\Entity\CollectionGroupPrice;

#[ORM\Entity]
class CollectionGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "integer")]
    private ?int $groupNumber = null;

    /**
     * @var Collection<int, CarpetCollection>
     */
    #[ORM\OneToMany(targetEntity: CarpetCollection::class, mappedBy: 'collectionGroup', cascade: ['persist', 'remove'])]
    private Collection $carpetCollections;

    /**
     * @var Collection<int, CollectionGroupPrice>
     */
    #[ORM\OneToMany(targetEntity: CollectionGroupPrice::class, mappedBy: 'collectionGroup', cascade: ['persist', 'remove'])]
    private Collection $collectionGroupPrices;

    public function __construct()
    {
        $this->carpetCollections = new ArrayCollection();
        $this->collectionGroupPrices = new ArrayCollection();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'groupNumber' => $this->groupNumber,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupNumber(): ?int
    {
        return $this->groupNumber;
    }

    public function setGroupNumber(?int $groupNumber): static
    {
        $this->groupNumber = $groupNumber;
        return $this;
    }

    /**
     * @return Collection<int, CarpetCollection>
     */
    public function getCarpetCollections(): Collection
    {
        return $this->carpetCollections;
    }

    public function addCarpetCollection(CarpetCollection $carpetCollection): static
    {
        if (!$this->carpetCollections->contains($carpetCollection)) {
            $this->carpetCollections->add($carpetCollection);
            $carpetCollection->setCollectionGroup($this);
        }
        return $this;
    }

    public function removeCarpetCollection(CarpetCollection $carpetCollection): static
    {
        if ($this->carpetCollections->removeElement($carpetCollection)) {
            if ($carpetCollection->getCollectionGroup() === $this) {
                $carpetCollection->setCollectionGroup(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, CollectionGroupPrice>
     */
    public function getCollectionGroupPrices(): Collection
    {
        return $this->collectionGroupPrices;
    }

    public function addCollectionGroupPrice(CollectionGroupPrice $collectionGroupPrice): static
    {
        if (!$this->collectionGroupPrices->contains($collectionGroupPrice)) {
            $this->collectionGroupPrices->add($collectionGroupPrice);
            $collectionGroupPrice->setCollectionGroup($this);
        }
        return $this;
    }

    public function removeCollectionGroupPrice(CollectionGroupPrice $collectionGroupPrice): static
    {
        if ($this->collectionGroupPrices->removeElement($collectionGroupPrice)) {
            if ($collectionGroupPrice->getCollectionGroup() === $this) {
                $collectionGroupPrice->setCollectionGroup(null);
            }
        }
        return $this;
    }
}
