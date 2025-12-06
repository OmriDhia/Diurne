<?php

namespace App\Setting\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CollectionGroupPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'collectionGroupPrices')]
    private ?CollectionGroup $collectionGroup = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $price_max = null;

    #[ORM\ManyToOne]
    private ?TarifGroup $tarifGroup = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCollectionGroup(): ?CollectionGroup
    {
        return $this->collectionGroup;
    }

    public function setCollectionGroup(?CollectionGroup $collectionGroup): static
    {
        $this->collectionGroup = $collectionGroup;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceMax(): ?string
    {
        return $this->price_max;
    }

    public function setPriceMax(?string $price_max): static
    {
        $this->price_max = $price_max;

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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'collectionGroup' => $this->getCollectionGroup() ? $this->getCollectionGroup()->getId() : null,
            'price' => $this->getPrice(),
            'price_max' => $this->getPriceMax(),
            'tarifGroup' => $this->getTarifGroup() ? $this->getTarifGroup()->getId() : null,
        ];
    }
}
