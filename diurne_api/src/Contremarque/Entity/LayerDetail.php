<?php

namespace App\Contremarque\Entity;

use App\Setting\Entity\Color;
use App\Setting\Entity\Material;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class LayerDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'layerDetails')]
    private ?Layer $layer = null;

    #[ORM\ManyToOne]
    private ?Color $color = null;

    #[ORM\ManyToOne]
    private ?Material $material = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $percentage = null;

    #[ORM\ManyToOne]
    private ?Thread $thread = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLayer(): ?Layer
    {
        return $this->layer;
    }

    public function setLayer(?Layer $layer): static
    {
        $this->layer = $layer;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): static
    {
        $this->color = $color;

        return $this;
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

    public function getPercentage(): ?string
    {
        return $this->percentage;
    }

    public function setPercentage(string $percentage): static
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    public function setThread(?Thread $thread): static
    {
        $this->thread = $thread;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'layer_id' => !empty($this->getLayer()) ? $this->getLayer()->getId() : null,
            'color' => !empty($this->getColor()) ? $this->getColor()->getId() : null,  // Assuming Color entity has getId method
            'material' => !empty($this->getMaterial()) ? $this->getMaterial()->getId() : null,  // Assuming Material entity has getId method
            'percentage' => $this->getPercentage(),
            'thread' => !empty($this->getThread()) ? $this->getThread()->toArray() : [],  // Assuming Thread entity has getId method
        ];
    }
}
