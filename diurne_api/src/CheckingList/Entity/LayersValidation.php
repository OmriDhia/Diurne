<?php
declare(strict_types=1);

namespace App\CheckingList\Entity;

use App\Contremarque\Entity\Layer;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class LayersValidation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne (inversedBy: "layersValidations")]
    private ?Layer $layer = null;

    #[ORM\ManyToOne(inversedBy: "layersValidations")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?CheckingList $checkingList = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $layerComment = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $layerValidation = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCheckingList(): ?CheckingList
    {
        return $this->checkingList;
    }

    public function getLayer(): ?Layer
    {
        return $this->layer;
    }

    public function setLayer(?Layer $layer): void
    {
        $this->layer = $layer;
    }

    public function setCheckingList(?CheckingList $checkingList): void
    {
        $this->checkingList = $checkingList;
    }

    public function getLayerComment(): ?string
    {
        return $this->layerComment;
    }

    public function setLayerComment(?string $layerComment): void
    {
        $this->layerComment = $layerComment;
    }

    public function isLayerValidation(): ?bool
    {
        return $this->layerValidation;
    }

    public function setLayerValidation(?bool $layerValidation): void
    {
        $this->layerValidation = $layerValidation;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'layerComment' => $this->layerComment,
            'layerValidation' => $this->layerValidation,
            'layer' => $this->layer?->getId(),
            'checkingList' => $this->checkingList?->getId(),
        ];
    }
}
