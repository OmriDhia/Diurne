<?php

namespace App\Contremarque\Entity;

use App\Contremarque\Repository\DesignerCompositionRepository;
use App\Setting\Entity\Material;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DesignerCompositionRepository::class)]
class DesignerComposition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Material $material = null;

    #[ORM\ManyToOne(inversedBy: 'designerCompositions')]
    private ?CarpetSpecification $carpetSpecification = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $rate = null;

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

    public function getCarpetSpecification(): ?CarpetSpecification
    {
        return $this->carpetSpecification;
    }

    public function setCarpetSpecification(?CarpetSpecification $carpetSpecification): static
    {
        $this->carpetSpecification = $carpetSpecification;

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
}
