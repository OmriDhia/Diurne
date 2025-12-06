<?php

namespace App\Contremarque\Entity\ImageCommand;

use App\Contremarque\Entity\CarpetStatus;
use DateTimeInterface;
use DateTime;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Entity\Sample;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class ImageCommand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'imageCommand', targetEntity: ImageCommandDesignerAssignment::class)]
    private Collection $imageCommandDesignerAssignments;

    #[ORM\OneToMany(mappedBy: 'imageCommand', targetEntity: TechnicalImage::class)]
    private Collection $technicalImages;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $commercialComment = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $advComment = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $rn = null;

    #[ORM\Column(type: Types::STRING, length: 50)]
    private string $commandNumber;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $studioComment = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $updatedAt;

    #[ORM\ManyToOne(targetEntity: CarpetDesignOrder::class, inversedBy: 'imageCommands')]
    #[ORM\JoinColumn(nullable: true)]
    private ?CarpetDesignOrder $carpetDesignOrder = null;

    #[ORM\ManyToOne(targetEntity: Sample::class, inversedBy: 'imageCommands')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Sample $sample = null;
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $canceledAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $canceledBy = null;

    #[ORM\ManyToOne]
    private ?CarpetStatus $status = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $transmition_date = null;

    #[ORM\Column(nullable: true)]
    private ?int $commercialId = null;

    public function __construct()
    {
        $this->imageCommandDesignerAssignments = new ArrayCollection();
        $this->technicalImages = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        error_log('onPrePersist triggered');
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }

    // Getter and Setter for id
    public function getId(): ?int
    {
        return $this->id;
    }

    // Removed setId() as it should not be manually set in a Doctrine entity

    // Getter and Setter for carpetDesignOrder
    public function getCarpetDesignOrder(): ?CarpetDesignOrder
    {
        return $this->carpetDesignOrder;
    }

    public function setCarpetDesignOrder(?CarpetDesignOrder $carpetDesignOrder): self
    {
        $this->carpetDesignOrder = $carpetDesignOrder;
        return $this;
    }

    // Getter and Setter for sample
    public function getSample(): ?Sample
    {
        return $this->sample;
    }

    public function setSample(?Sample $sample): self
    {
        $this->sample = $sample;
        return $this;
    }

    // Getter and Add/Remove for imageCommandDesignerAssignments
    public function getImageCommandDesignerAssignments(): Collection
    {
        return $this->imageCommandDesignerAssignments;
    }

    // Removed setImageCommandDesignerAssignments() to avoid overwriting the collection directly
    public function addImageCommandDesignerAssignment(ImageCommandDesignerAssignment $assignment): self
    {
        if (!$this->imageCommandDesignerAssignments->contains($assignment)) {
            $this->imageCommandDesignerAssignments->add($assignment);
            $assignment->setImageCommand($this);
        }
        return $this;
    }

    public function removeImageCommandDesignerAssignment(ImageCommandDesignerAssignment $assignment): self
    {
        if ($this->imageCommandDesignerAssignments->removeElement($assignment)) {
            if ($assignment->getImageCommand() === $this) {
                $assignment->setImageCommand(null);
            }
        }
        return $this;
    }

    // Getter and Add/Remove for technicalImages
    public function getTechnicalImages(): Collection
    {
        return $this->technicalImages;
    }

    // Removed setTechnicalImages() to avoid overwriting the collection directly
    public function addTechnicalImage(TechnicalImage $technicalImage): self
    {
        if (!$this->technicalImages->contains($technicalImage)) {
            $this->technicalImages->add($technicalImage);
            $technicalImage->setImageCommand($this);
        }
        return $this;
    }

    public function removeTechnicalImage(TechnicalImage $technicalImage): self
    {
        if ($this->technicalImages->removeElement($technicalImage)) {
            if ($technicalImage->getImageCommand() === $this) {
                $technicalImage->setImageCommand(null);
            }
        }
        return $this;
    }

    // Getter and Setter for commercialComment
    public function getCommercialComment(): ?string
    {
        return $this->commercialComment;
    }

    public function setCommercialComment(?string $commercialComment): self
    {
        $this->commercialComment = $commercialComment;
        return $this;
    }

    // Getter and Setter for advComment
    public function getAdvComment(): ?string
    {
        return $this->advComment;
    }

    public function setAdvComment(?string $advComment): self
    {
        $this->advComment = $advComment;
        return $this;
    }

    // Getter and Setter for rn
    public function getRn(): ?string
    {
        return $this->rn;
    }

    public function setRn(?string $rn): self
    {
        $this->rn = $rn;
        return $this;
    }

    // Getter and Setter for commandNumber
    public function getCommandNumber(): string
    {
        return $this->commandNumber;
    }

    public function setCommandNumber(string $commandNumber): self
    {
        $this->commandNumber = $commandNumber;
        return $this;
    }

    // Getter and Setter for studioComment
    public function getStudioComment(): ?string
    {
        return $this->studioComment;
    }

    public function setStudioComment(?string $studioComment): self
    {
        $this->studioComment = $studioComment;
        return $this;
    }

    // Getter and Setter for createdAt
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // Getter and Setter for updatedAt
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    // Implement getReferencedEntity() (used in CarpetDesignOrder and toArray)
    public function getReferencedEntity(): ?object
    {
        return $this->carpetDesignOrder ?? $this->sample;
    }

    public function getCanceledAt(): ?DateTimeInterface
    {
        return $this->canceledAt;
    }

    public function setCanceledAt(?DateTimeInterface $canceledAt): void
    {
        $this->canceledAt = $canceledAt;
    }

    public function getCanceledBy(): ?string
    {
        return $this->canceledBy;
    }

    public function setCanceledBy(?string $canceledBy): void
    {
        $this->canceledBy = $canceledBy;
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

    public function getTransmitionDate(): ?DateTimeInterface
    {
        return $this->transmition_date;
    }

    public function setTransmitionDate(?DateTimeInterface $transmition_date): void
    {
        $this->transmition_date = $transmition_date;
    }

    public function getCommercialId(): ?int
    {
        return $this->commercialId;
    }

    public function setCommercialId(?int $commercialId): void
    {
        $this->commercialId = $commercialId;
    }

    public function getCurrentCommercialId(): ?int
    {
        // Pour les demandes d'image, on récupère le commercial via la contremarque
        if ($this->carpetDesignOrder) {
            $projectDi = $this->carpetDesignOrder->getProjectDi();
            if ($projectDi) {
                $contremarque = $projectDi->getContremarque();
                if ($contremarque) {
                    return $contremarque->getCurrentCommercialId();
                }
            }
        }

        return null;
    }

    public function toArray(): array
    {
        $images = [];
        if ($this->carpetDesignOrder) {
            foreach ($this->carpetDesignOrder->getImages() as $attachment) {
                $imageData = $attachment->toArray();
                if (isset($imageData['imageType']['name']) && str_contains($imageData['imageType']['name'], 'Légende')) {
                    $images[] = $imageData;
                }
            }
        }
        return [
            'id' => $this->id,
            'commercialComment' => $this->commercialComment,
            'advComment' => $this->advComment,
            'rn' => $this->rn,
            'commandNumber' => $this->commandNumber,
            'studioComment' => $this->studioComment,
            'imageCommandDesignerAssignments' => $this->imageCommandDesignerAssignments->map(
                fn($assignment) => $assignment->toArray()
            )->toArray(),
            'technicalImages' => $this->technicalImages->map(
                fn($image) => $image->toArray()
            )->toArray(),
            'carpetSpecification' => $this->carpetDesignOrder->getCarpetSpecification()?->toArray(),
            'images' => $images,
            'createdAt' => $this->createdAt->format(DateTimeInterface::ISO8601),
            'updatedAt' => $this->updatedAt->format(DateTimeInterface::ISO8601),
            'carpetDesignOrder' => $this->carpetDesignOrder ? $this->carpetDesignOrder->toArray() : null,
            'sample' => $this->sample ? $this->sample->toArray() : null,
            'referencedEntity' => $this->getReferencedEntity() ? $this->getReferencedEntity()->toArray() : null,
            'status' => $this->status ? $this->status->toArray() : null,
            'commercialId' => $this->getCurrentCommercialId(),
            'transmition_date' => $this->getTransmitionDate(),
        ];
    }
}
