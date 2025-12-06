<?php

namespace App\Workshop\Entity;

use App\Setting\Entity\Material;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class WorkshopInformationMaterial
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Material $material = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $rate = null;

    #[ORM\ManyToOne(inversedBy: 'workshopInformationMaterials')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WorkshopInformation $workshopInformation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, options: ['default' => '0.000000'])]
    private ?string $price = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(string $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getWorkshopInformation(): ?WorkshopInformation
    {
        return $this->workshopInformation;
    }

    public function setWorkshopInformation(?WorkshopInformation $workshopInformation): static
    {
        $this->workshopInformation = $workshopInformation;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'material' => $this->material?->toArray(),
            'rate' => $this->getRate(),
            'price' => $this->getPrice(),
            'workshopInformationId' => $this->workshopInformation?->getId(),
        ];
    }
}
