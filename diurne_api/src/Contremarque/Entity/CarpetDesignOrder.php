<?php

namespace App\Contremarque\Entity;

use DateTimeInterface;
use App\Contremarque\Entity\ImageCommand\ImageCommand;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CarpetDesignOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'carpetDesignOrders')]
    private ?ProjectDi $projectDi = null;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'carpetDesignOrders')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Location $location = null;

    #[ORM\ManyToOne]
    private ?CarpetStatus $status = null;

    /**
     * @var Collection<int, DesignerAssignment>
     */
    #[ORM\OneToMany(targetEntity: DesignerAssignment::class, mappedBy: 'carpetDesignOrder', cascade: ['persist', 'remove'])]
    private Collection $designers;

    #[ORM\OneToOne(inversedBy: 'carpetDesignOrder', cascade: ['remove', 'persist'])]
    private ?CarpetSpecification $carpetSpecification = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(mappedBy: 'carpetDesignOrder', targetEntity: Image::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $images;

    /**
     * @var Collection<int, CarpetDesignOrderAttachment>
     */
    #[ORM\OneToMany(targetEntity: CarpetDesignOrderAttachment::class, mappedBy: 'carpetDesignOrder', cascade: ['persist', 'remove'])]
    private Collection $carpetDesignOrderAttachments;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $variation = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $variation_image_reference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modelName = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $jpeg = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $impression = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $impressionBarreDeLaine = null;

    #[ORM\OneToOne(targetEntity: CustomerInstruction::class, mappedBy: 'carpetDesignOrder', cascade: ['persist', 'remove'])]
    private ?CustomerInstruction $customerInstruction = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $transmition_date = null;

    #[ORM\Column(nullable: true)]
    private ?int $variatedFromOrderId = null;

    /**
     * @var Collection<int, ImageCommand>
     */
    #[ORM\OneToMany(targetEntity: ImageCommand::class, mappedBy: 'carpetDesignOrder', cascade: ['persist', 'remove'])]
    private Collection $imageCommands; // Removed ?Collection since it should never be null

    #[ORM\OneToMany(mappedBy: 'carpetDesignOrder', targetEntity: Sample::class, cascade: ['persist', 'remove'])]
    private Collection $samples;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $isSampleContainer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $deletedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $deletedBy = null;


    public function __construct()
    {
        $this->designers = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->carpetDesignOrderAttachments = new ArrayCollection();
        $this->imageCommands = new ArrayCollection();
        $this->samples = new ArrayCollection();
    }

    // Detach parent relationships
    public function detachParents(): void
    {
        $this->setProjectDi(null);
        $this->setLocation(null);
        $this->setStatus(null);
    }

    // Getter and Setter for customerInstruction
    public function getCustomerInstruction(): ?CustomerInstruction
    {
        return $this->customerInstruction;
    }

    public function setCustomerInstruction(?CustomerInstruction $customerInstruction): self
    {
        if ($this->customerInstruction !== $customerInstruction) {
            $this->customerInstruction = $customerInstruction;
            if ($customerInstruction !== null) {
                $customerInstruction->setCarpetDesignOrder($this);
            }
        }
        return $this;
    }

    // Corrected getter and setter for imageCommands

    /**
     * @return Collection<int, ImageCommand>
     */
    public function getImageCommands(): Collection
    {
        return $this->imageCommands;
    }

    public function addImageCommand(ImageCommand $imageCommand): self
    {
        if (!$this->imageCommands->contains($imageCommand)) {
            $this->imageCommands->add($imageCommand);
            $imageCommand->setCarpetDesignOrder($this);
        }

        return $this;
    }

    public function removeImageCommand(ImageCommand $imageCommand): self
    {
        if ($this->imageCommands->removeElement($imageCommand)) {
            // Set the owning side to null (unless already changed)
            if ($imageCommand->getCarpetDesignOrder() === $this) {
                $imageCommand->setCarpetDesignOrder(null);
            }
        }

        return $this;
    }

    // Existing methods (getters, setters, add/remove methods) remain unchanged...

    public function getSamples(): Collection
    {
        return $this->samples;
    }

    public function addSample(Sample $sample): self
    {
        if (!$this->samples->contains($sample)) {
            $this->samples[] = $sample;
            $sample->setCarpetDesignOrder($this);
        }

        return $this;
    }

    public function removeSample(Sample $sample): self
    {
        if ($this->samples->removeElement($sample)) {
            if ($sample->getCarpetDesignOrder() === $this) {
                $sample->setCarpetDesignOrder(null);
            }
        }

        return $this;
    }

    public function addDesigner(DesignerAssignment $designer): static
    {
        if (!$this->designers->contains($designer)) {
            $this->designers->add($designer);
            $designer->setCarpetDesignOrder($this);
        }

        return $this;
    }

    public function removeDesigner(DesignerAssignment $designer): static
    {
        if ($this->designers->removeElement($designer)) {
            if ($designer->getCarpetDesignOrder() === $this) {
                $designer->setCarpetDesignOrder(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        $designerData = [];
        foreach ($this->getDesigners() as $designer) {
            $designerData[] = $designer->toArray();
        }

        return [
            'id' => $this->getId(),
            'projectDi' => null !== $this->getProjectDi() ? $this->getProjectDi()->getId() : null,
            'location' => null !== $this->getLocation() ? $this->getLocation()->toArray() : null,
            'status' => null !== $this->getStatus() ? $this->getStatus()->toArray() : null,
            'designers' => $designerData,
            'carpetSpecification' => null !== $this->getCarpetSpecification() ? $this->getCarpetSpecification()->toArray() : null,
            'customerInstruction' => $this->getCustomerInstruction() ? $this->getCustomerInstruction()->toArray() : null,
            'variation' => $this->getVariation(),
            'variationImageReference' => $this->getVariationImageReference(),
            'modelName' => $this->getModelName(),
            'jpeg' => $this->isJpeg(),
            'impression' => $this->isImpression(),
            'impressionBarreDeLaine' => $this->isImpressionBarreDeLaine(),
            'transmition_date' => $this->getTransmitionDate(),
            'isSampleContainer' => $this->isSampleContainer(),
        ];
    }

    /**
     * @return Collection<int, DesignerAssignment>
     */
    public function getDesigners(): Collection
    {
        return $this->designers;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectDi(): ?ProjectDi
    {
        return $this->projectDi;
    }

    public function setProjectDi(?ProjectDi $projectDi): static
    {
        $this->projectDi = $projectDi;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getStatus(): ?CarpetStatus
    {
        return $this->status;
    }

    public function setStatus(?CarpetStatus $status): static
    {
        $this->status = $status;

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

    public function getVariation(): ?string
    {
        return $this->variation;
    }

    public function setVariation(?string $variation): static
    {
        $this->variation = $variation;

        return $this;
    }

    public function getVariationImageReference(): ?string
    {
        return $this->variation_image_reference;
    }

    public function setVariationImageReference(?string $variation_image_reference): static
    {
        $this->variation_image_reference = $variation_image_reference;

        return $this;
    }

    public function getModelName(): ?string
    {
        return $this->modelName;
    }

    public function setModelName(?string $modelName): static
    {
        $this->modelName = $modelName;

        return $this;
    }

    public function isJpeg(): ?bool
    {
        return $this->jpeg;
    }

    public function setJpeg(?bool $jpeg): static
    {
        $this->jpeg = $jpeg;

        return $this;
    }

    public function isImpression(): ?bool
    {
        return $this->impression;
    }

    public function setImpression(?bool $impression): static
    {
        $this->impression = $impression;

        return $this;
    }

    public function isImpressionBarreDeLaine(): ?bool
    {
        return $this->impressionBarreDeLaine;
    }

    public function setImpressionBarreDeLaine(?bool $impressionBarreDeLaine): static
    {
        $this->impressionBarreDeLaine = $impressionBarreDeLaine;

        return $this;
    }

    public function getTransmitionDate(): ?DateTimeInterface
    {
        return $this->transmition_date;
    }

    public function setTransmitionDate(?DateTimeInterface $transmition_date): void
    {
        $this->transmition_date = $transmition_date;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setCarpetDesignOrder($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            if ($image->getCarpetDesignOrder() === $this) {
                $image->setCarpetDesignOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CarpetDesignOrderAttachment>
     */
    public function getCarpetDesignOrderAttachments(): Collection
    {
        return $this->carpetDesignOrderAttachments;
    }

    public function addCarpetDesignOrderAttachment(CarpetDesignOrderAttachment $carpetDesignOrderAttachment): static
    {
        if (!$this->carpetDesignOrderAttachments->contains($carpetDesignOrderAttachment)) {
            $this->carpetDesignOrderAttachments->add($carpetDesignOrderAttachment);
            $carpetDesignOrderAttachment->setCarpetDesignOrder($this);
        }

        return $this;
    }

    public function removeCarpetDesignOrderAttachment(CarpetDesignOrderAttachment $carpetDesignOrderAttachment): static
    {
        if ($this->carpetDesignOrderAttachments->removeElement($carpetDesignOrderAttachment)) {
            if ($carpetDesignOrderAttachment->getCarpetDesignOrder() === $this) {
                $carpetDesignOrderAttachment->setCarpetDesignOrder(null);
            }
        }

        return $this;
    }

    public function resetUniqueFields(): void
    {
        $this->id = null; // Doctrine will auto-generate a new ID
    }

    public function getVariatedFromOrderId(): ?int
    {
        return $this->variatedFromOrderId;
    }

    public function setVariatedFromOrderId(?int $variatedFromOrderId): static
    {
        $this->variatedFromOrderId = $variatedFromOrderId;

        return $this;
    }

    public function isSampleContainer(): ?bool
    {
        return $this->isSampleContainer;
    }

    public function setIsSampleContainer(?bool $isSampleContainer): static
    {
        $this->isSampleContainer = $isSampleContainer;
        return $this;
    }

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function getDeletedBy(): ?string
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(?string $deletedBy): self
    {
        $this->deletedBy = $deletedBy;
        return $this;
    }
}
