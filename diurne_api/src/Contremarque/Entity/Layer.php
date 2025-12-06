<?php

namespace App\Contremarque\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Layer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $layer_number = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $remarque = null;

    /**
     * @var Collection<int, LayerDetail>
     */
    #[ORM\OneToMany(targetEntity: LayerDetail::class, mappedBy: 'layer', cascade: ['persist', 'remove'])]
    private Collection $layerDetails;

    #[ORM\ManyToOne(inversedBy: 'layers')]
    private ?CarpetComposition $carpetComposition = null;

    public function __construct()
    {
        $this->layerDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLayerNumber(): ?int
    {
        return $this->layer_number;
    }

    public function setLayerNumber(int $layer_number): static
    {
        $this->layer_number = $layer_number;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque): static
    {
        $this->remarque = $remarque;

        return $this;
    }

    /**
     * @return Collection<int, LayerDetail>
     */
    public function getLayerDetails(): Collection
    {
        return $this->layerDetails;
    }

    public function addLayerDetail(LayerDetail $layerDetail): static
    {
        if (!$this->layerDetails->contains($layerDetail)) {
            $this->layerDetails->add($layerDetail);
            $layerDetail->setLayer($this);
        }

        return $this;
    }

    public function removeLayerDetail(LayerDetail $layerDetail): static
    {
        if ($this->layerDetails->removeElement($layerDetail)) {
            // set the owning side to null (unless already changed)
            if ($layerDetail->getLayer() === $this) {
                $layerDetail->setLayer(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'layer_number' => $this->getLayerNumber(),
            'remarque' => $this->getRemarque(),
            //            'carpet_composition' => $this->getCarpetComposition() ? $this->getCarpetComposition()->getId() : null, // Assuming you want to include the CarpetComposition ID
            'layer_details' => array_map(fn (LayerDetail $detail) => $detail->toArray(), $this->getLayerDetails()->toArray()),
        ];
    }

    public function getCarpetComposition(): ?CarpetComposition
    {
        return $this->carpetComposition;
    }

    public function setCarpetComposition(?CarpetComposition $carpetComposition): static
    {
        $this->carpetComposition = $carpetComposition;

        return $this;
    }
}
