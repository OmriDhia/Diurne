<?php

namespace App\Contremarque\Entity;

use DateTimeImmutable;
use App\Setting\Entity\ImageType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Index(name: 'idx_it_name', columns: ['image_reference'])]
#[ORM\Index(name: 'idx_it_image_type', columns: ['image_type_id'])]
#[ORM\Index(name: 'idx_it_carpet_design_order', columns: ['carpet_design_order_id'])]
#[ORM\Index(name: 'idx_it_is_validated', columns: ['is_validated'])]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $image_reference = null;

    #[ORM\ManyToOne(targetEntity: CarpetDesignOrder::class, inversedBy: 'images')]
    private ?CarpetDesignOrder $carpetDesignOrder = null;

    #[ORM\Column]
    private ?bool $isValidated = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasError = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $error = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column]
    private ?DateTimeImmutable $validatedAt = null;

    #[ORM\OneToOne(mappedBy: 'image', cascade: ['persist'])]
    private ?ImageAttachment $imageAttachment = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?ImageType $imageType = null;
    /**
     * @var Collection<int, Sample>
     */
    #[ORM\ManyToMany(targetEntity: Sample::class, mappedBy: 'images')]
    private Collection $samples;

    public function __construct()
    {
        $this->samples = new ArrayCollection();
    }

    public function setValidated(bool $isValidated): static
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    public function setHasError(?bool $hasError): static
    {
        $this->hasError = $hasError;

        return $this;
    }

    public function toArray(): array
    {
        $attachmentData = [];
        $imageAttachment = $this->getImageAttachment();
        if (!empty($imageAttachment)) {
            $attachmentData = $imageAttachment->getAttachment()->toArray();
        }

        return [
            'id' => $this->getId(),
            'image_reference' => $this->getImageReference(),
            'carpetDesignOrder' => $this->getCarpetDesignOrder() ? $this->getCarpetDesignOrder()->getId() : null,
            'is_validated' => $this->isValidated(),
            'has_error' => $this->hasError(),
            'error' => $this->getError(),
            'commentaire' => $this->getCommentaire(),
            'validated_at' => $this->getValidatedAt() ? $this->getValidatedAt()->format('Y-m-d H:i:s') : null,
            'attachment' => $attachmentData,
            'imageType' => $this->getImageType()->toArray(),
        ];
    }

    public function getImageAttachment(): ?ImageAttachment
    {
        return $this->imageAttachment;
    }

    public function setImageAttachment(?ImageAttachment $imageAttachment): static
    {
        // unset the owning side of the relation if necessary
        if (null === $imageAttachment && null !== $this->imageAttachment) {
            $this->imageAttachment->setImage(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $imageAttachment && $imageAttachment->getImage() !== $this) {
            $imageAttachment->setImage($this);
        }

        $this->imageAttachment = $imageAttachment;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageReference(): ?string
    {
        return $this->image_reference;
    }

    public function setImageReference(string $image_reference): static
    {
        $this->image_reference = $image_reference;

        return $this;
    }

    public function getCarpetDesignOrder(): ?CarpetDesignOrder
    {
        return $this->carpetDesignOrder;
    }

    public function setCarpetDesignOrder(?CarpetDesignOrder $carpetDesignOrder): static
    {
        $this->carpetDesignOrder = $carpetDesignOrder;

        return $this;
    }

    public function isValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function hasError(): ?bool
    {
        return $this->hasError;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(?string $error): static
    {
        $this->error = $error;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getValidatedAt(): ?DateTimeImmutable
    {
        return $this->validatedAt;
    }

    public function setValidatedAt(DateTimeImmutable $validatedAt): static
    {
        $this->validatedAt = $validatedAt;

        return $this;
    }

    public function getImageType(): ?ImageType
    {
        return $this->imageType;
    }

    public function setImageType(?ImageType $imageType): static
    {
        $this->imageType = $imageType;

        return $this;
    }

    // Replace the existing sample-related methods with these:
    public function getSamples(): Collection
    {
        return $this->samples;
    }

    public function addSample(Sample $sample): self
    {
        if (!$this->samples->contains($sample)) {
            $this->samples->add($sample);
            $sample->addImage($this);
        }
        return $this;
    }

    public function removeSample(Sample $sample): self
    {
        if ($this->samples->removeElement($sample)) {
            $sample->removeImage($this);
        }
        return $this;
    }

    public function __clone()
    {
        $this->id = null;
        $this->carpetDesignOrder = null;
        $this->samples = new ArrayCollection();
        $this->imageAttachment = null;
    }
}
