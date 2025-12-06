<?php

namespace App\Workshop\Entity;


use App\Workshop\Repository\MaterialPurchasePriceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Workshop\Entity\WorkshopOrder;
use App\Workshop\Entity\WorkshopInformation;

#[ORM\Entity]
class MaterialPurchasePrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $material_id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $price = null;

    #[ORM\ManyToOne(targetEntity: WorkshopOrder::class, inversedBy: 'materialPurchasePrices')]
    #[ORM\JoinColumn(name: 'production_order_id', nullable: false)]
    private ?WorkshopOrder $workshopOrder = null;
    #[ORM\ManyToOne(targetEntity: WorkshopInformation::class, inversedBy: 'materialPurchasePrice')]
    #[ORM\JoinColumn(name: 'workshop_order_id', nullable: false)]
    private ?WorkshopInformation $workshopInformation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkshopInformation(): ?WorkshopInformation
    {
        return $this->workshopInformation;
    }

    public function setWorkshopInformation(?WorkshopInformation $WorkshopInformation): void
    {
        $this->workshopInformation = $WorkshopInformation;
    }

    public function getMaterialId(): ?int
    {
        return $this->material_id;
    }

    public function setMaterialId(int $material_id): static
    {
        $this->material_id = $material_id;

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

    public function getWorkshopOrder(): ?WorkshopOrder
    {
        return $this->workshopOrder;
    }

    public function setWorkshopOrder(?WorkshopOrder $workshopOrder): static
    {
        $this->workshopOrder = $workshopOrder;

        return $this;
    }

    public function toArray()
    {
        return [
            "id" => $this->id,
            "material_id" => $this->material_id,
            "price" => $this->price,
            "production_order_id" => $this->workshopOrder?->getId(),
            "workshop_information" => $this->workshopInformation->getId()
        ];
    }

}
