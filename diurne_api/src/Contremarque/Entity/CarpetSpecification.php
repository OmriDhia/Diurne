<?php

declare(strict_types=1);

namespace App\Contremarque\Entity;

use App\Setting\Entity\CarpetCollection;
use App\Setting\Entity\Model;
use App\Setting\Entity\Quality;
use App\Setting\Entity\SpecialShape;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CarpetSpecification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne]
    private ?CarpetCollection $collection = null;

    #[ORM\ManyToOne]
    private ?Model $model = null;

    #[ORM\ManyToOne]
    private ?Quality $quality = null;

    #[ORM\Column(nullable: true)]
    private ?bool $has_special_shape = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_oversized = null;

    #[ORM\ManyToOne]
    private ?SpecialShape $specialShape = null;

    /**
     * @var Collection<int, CarpetDimension>
     */
    #[ORM\OneToMany(targetEntity: CarpetDimension::class, mappedBy: 'carpetSpecification', cascade: ['persist', 'remove'])]
    private Collection $carpetDimensions;

    #[ORM\OneToOne(mappedBy: 'carpetSpecification', cascade: ['persist', 'remove'])]
    private ?CarpetDesignOrder $carpetDesignOrder = null;

    /**
     * @var Collection<int, CarpetMaterial>
     */
    #[ORM\OneToMany(targetEntity: CarpetMaterial::class, mappedBy: 'carpetSpecification', cascade: ['persist', 'remove'])]
    private Collection $materials;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $carpetReference = null;

    /**
     * @var Collection<int, DesignerComposition>
     */
    #[ORM\OneToMany(targetEntity: DesignerComposition::class, mappedBy: 'carpetSpecification', cascade: ['persist', 'remove'])]
    private Collection $designerCompositions;

    #[ORM\OneToOne(mappedBy: 'carpetSpecification', cascade: ['persist', 'remove'])]
    private ?CarpetComposition $carpetComposition = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $globalWeight = null;

    public function __construct()
    {
        $this->carpetDimensions = new ArrayCollection();
        $this->materials = new ArrayCollection();
        $this->designerCompositions = new ArrayCollection();
    }

    public function __clone()
    {
        $this->id = null;
        $this->carpetDimensions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCollection(): ?CarpetCollection
    {
        return $this->collection;
    }

    public function setCollection(?CarpetCollection $collection): static
    {
        $this->collection = $collection;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getQuality(): ?Quality
    {
        return $this->quality;
    }

    public function setQuality(?Quality $quality): static
    {
        $this->quality = $quality;

        return $this;
    }

    public function hasSpecialShape(): ?bool
    {
        return $this->has_special_shape;
    }

    public function setHasSpecialShape(bool $has_special_shape): static
    {
        $this->has_special_shape = $has_special_shape;

        return $this;
    }

    public function isOversized(): ?bool
    {
        return $this->is_oversized;
    }

    public function setOversized(bool $is_oversized): static
    {
        $this->is_oversized = $is_oversized;

        return $this;
    }

    public function getSpecialShape(): ?SpecialShape
    {
        return $this->specialShape;
    }

    public function setSpecialShape(?SpecialShape $specialShape): static
    {
        $this->specialShape = $specialShape;

        return $this;
    }

    public function toArray(): array
    {
        $dimensions = [];
        if ($this->getCarpetDimensions()->count()) {
            foreach ($this->getCarpetDimensions() as $carpetDimension) {
                if ($carpetDimension->getMesurement() === null) {
                    continue;
                }
                $measurement = $carpetDimension->getMesurement();
                $dimensions[$measurement->getId()] = [];
                $dimensionValues = $carpetDimension->getDimensionValues();

                if ($dimensionValues->count()) {
                    foreach ($dimensionValues as $index => $dimensionValue) {
                        if (null !== $dimensionValue) {
                            $dimensions[$measurement->getId()][$index] = [
                                'unit_id' => !empty($dimensionValue->getUnit()) ? $dimensionValue->getUnit()->getId() : 0,
                                'unit_name' => !empty($dimensionValue->getUnit()) ? $dimensionValue->getUnit()->getName() : null,
                                'unit_abbreviation' => !empty($dimensionValue->getUnit()) ? $dimensionValue->getUnit()->getAbbreviation() : null,
                                'value' => $dimensionValue->getValue(),
                            ];
                        }
                    }
                }
            }
        }

        $materials = [];

        if ($this->getMaterials()->count()) {
            foreach ($this->getMaterials() as $index => $material) {
                $materials[$material->getMaterial()->getId()]['id'] = $material->getId();
                $materials[$material->getMaterial()->getId()]['material_id'] = $material->getMaterial()->getId();
                $materials[$material->getMaterial()->getId()]['reference'] = $material->getMaterial()->getReference();
                $materials[$material->getMaterial()->getId()]['carpetSpecification_id'] = $this->getId();
                $materials[$material->getMaterial()->getId()]['rate'] = $material->getRate();
            }
        }
        $designerMaterials = [];
        if ($this->getDesignerCompositions()->count()) {
            foreach ($this->getDesignerCompositions() as $index => $material) {
                $designerMaterials[$index]['material_id'] = $material->getMaterial()->getId();
                $designerMaterials[$index]['reference'] = $material->getMaterial()->getReference();
                $designerMaterials[$index]['carpetSpecification_id'] = $this->getId();
                $designerMaterials[$index]['rate'] = $material->getRate();
            }
        }
        $carpedComposition = [];

        if (!empty($this->getCarpetComposition())) {
            $carpedComposition = $this->getCarpetComposition()->toArray();
        }

        return [
            'id' => $this->getId(),
            'description' => $this->getDescription(),
            'collection' => $this->getCollection() ? $this->getCollection()->toArray() : null,
            'model' => $this->getModel() ? $this->getModel()->toArray() : null,
            'quality' => $this->getQuality() ? $this->getQuality()->toArray() : null,
            'has_special_shape' => $this->hasSpecialShape(),
            'is_oversized' => $this->isOversized(),
            'specialShape' => $this->getSpecialShape() ? $this->getSpecialShape()->toArray() : null,
            'carpetDimensions' => $dimensions,
            'carpetMaterials' => $materials,
            'designMaterials' => $designerMaterials,
            'carpedComposition' => $carpedComposition,
            'weight' => $this->getGlobalWeight(),
            'reference' => $this->getCarpetReference()

        ];
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
            $carpetDimension->setCarpetSpecification($this);
        }

        return $this;
    }

    public function removeCarpetDimension(CarpetDimension $carpetDimension): static
    {
        if ($this->carpetDimensions->removeElement($carpetDimension)) {
            if ($carpetDimension->getCarpetSpecification() === $this) {
                $carpetDimension->setCarpetSpecification(null);
            }
        }

        return $this;
    }

    public function getCarpetDesignOrder(): ?CarpetDesignOrder
    {
        return $this->carpetDesignOrder;
    }

    public function setCarpetDesignOrder(?CarpetDesignOrder $carpetDesignOrder): static
    {
        if (null === $carpetDesignOrder && null !== $this->carpetDesignOrder) {
            $this->carpetDesignOrder->setCarpetSpecification(null);
        }

        if (null !== $carpetDesignOrder && $carpetDesignOrder->getCarpetSpecification() !== $this) {
            $carpetDesignOrder->setCarpetSpecification($this);
        }

        $this->carpetDesignOrder = $carpetDesignOrder;

        return $this;
    }

    /**
     * @return Collection<int, CarpetMaterial>
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(CarpetMaterial $material): static
    {
        if (!$this->materials->contains($material)) {
            $this->materials->add($material);
            $material->setCarpetSpecification($this);
        }

        return $this;
    }

    public function removeMaterial(CarpetMaterial $material): static
    {
        if ($this->materials->removeElement($material)) {
            // set the owning side to null (unless already changed)
            if ($material->getCarpetSpecification() === $this) {
                $material->setCarpetSpecification(null);
            }
        }

        return $this;
    }

    public function getCarpetReference(): ?string
    {
        return $this->carpetReference;
    }

    public function setCarpetReference(string $carpetReference): static
    {
        $this->carpetReference = $carpetReference;

        return $this;
    }

    /**
     * @return Collection<int, DesignerComposition>
     */
    public function getDesignerCompositions(): Collection
    {
        return $this->designerCompositions;
    }

    public function addDesignerComposition(DesignerComposition $designerComposition): static
    {
        if (!$this->designerCompositions->contains($designerComposition)) {
            $this->designerCompositions->add($designerComposition);
            $designerComposition->setCarpetSpecification($this);
        }

        return $this;
    }

    public function removeDesignerComposition(DesignerComposition $designerComposition): static
    {
        if ($this->designerCompositions->removeElement($designerComposition)) {
            if ($designerComposition->getCarpetSpecification() === $this) {
                $designerComposition->setCarpetSpecification(null);
            }
        }

        return $this;
    }

    public function getCarpetComposition(): ?CarpetComposition
    {
        return $this->carpetComposition;
    }

    public function setCarpetComposition(?CarpetComposition $carpetComposition): static
    {
        // unset the owning side of the relation if necessary
        if (null === $carpetComposition && null !== $this->carpetComposition) {
            $this->carpetComposition->setCarpetSpecification(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $carpetComposition && $carpetComposition->getCarpetSpecification() !== $this) {
            $carpetComposition->setCarpetSpecification($this);
        }

        $this->carpetComposition = $carpetComposition;

        return $this;
    }

    public function getGlobalWeight(): ?string
    {
        return $this->globalWeight;
    }

    public function setGlobalWeight(?string $globalWeight): static
    {
        $this->globalWeight = $globalWeight;

        return $this;
    }

    public function resetUniqueFields(): void
    {
        $this->id = null; // Doctrine will auto-generate a new ID
    }
}
