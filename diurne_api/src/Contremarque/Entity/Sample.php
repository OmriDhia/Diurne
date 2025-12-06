<?php

namespace App\Contremarque\Entity;

use DateTimeInterface;
use DateTime;
use App\Contremarque\Entity\ImageCommand\ImageCommand;
use App\Contremarque\ValueObject\Dimension;
use App\Setting\Entity\CarpetCollection;
use App\Setting\Entity\Model;
use App\Setting\Entity\Quality;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'samples')]
#[ORM\Index(columns: ['di_command_number'])]
#[ORM\Index(columns: ['location_id'])]
#[ORM\Index(columns: ['status_id'])]
#[ORM\HasLifecycleCallbacks]
class Sample
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CarpetDesignOrder::class, inversedBy: 'samples')]
    #[ORM\JoinColumn(nullable: true)]
    private ?CarpetDesignOrder $carpetDesignOrder = null;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'samples')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Location $location = null;

    #[ORM\ManyToOne(targetEntity: CarpetCollection::class)]
    #[ORM\JoinColumn(name: 'collection_id', referencedColumnName: 'id', nullable: true)]
    private ?CarpetCollection $collection = null;

    #[ORM\ManyToOne(targetEntity: Model::class, inversedBy: 'samples')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Model $model = null;

    #[ORM\ManyToOne(targetEntity: CarpetStatus::class)]
    #[ORM\JoinColumn(name: 'status_id', referencedColumnName: 'id', nullable: false)]
    private CarpetStatus $status;

    #[ORM\ManyToOne(targetEntity: Quality::class, inversedBy: 'samples')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Quality $quality = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\ManyToMany(targetEntity: Image::class, inversedBy: 'samples')]
    #[ORM\JoinTable(name: 'sample_images')]
    #[ORM\JoinColumn(name: 'sample_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'image_id', referencedColumnName: 'id')]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'sample', targetEntity: Attachment::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $attachments;

    #[ORM\OneToMany(mappedBy: 'sample', targetEntity: ImageCommand::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $imageCommands;

    #[ORM\Column(length: 50)]
    private string $diCommandNumber;

    #[ORM\Embedded(class: Dimension::class)]
    private Dimension $dimension;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $rn = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $transmissionDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $customerComment = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->imageCommands = new ArrayCollection();
        $this->dimension = new Dimension('0', '0');
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarpetDesignOrder(): ?CarpetDesignOrder
    {
        return $this->carpetDesignOrder;
    }

    public function setCarpetDesignOrder(?CarpetDesignOrder $carpetDesignOrder): self
    {
        $this->carpetDesignOrder = $carpetDesignOrder;
        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getCollection(): ?CarpetCollection
    {
        return $this->collection;
    }

    public function setCollection(?CarpetCollection $collection): self
    {
        $this->collection = $collection;
        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function getStatus(): ?CarpetStatus
    {
        return $this->status;
    }

    public function setStatus(CarpetStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getQuality(): ?Quality
    {
        return $this->quality;
    }

    public function setQuality(Quality $quality): self
    {
        $this->quality = $quality;
        return $this;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->addSample($this);
        }
        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            $image->removeSample($this);
        }
        return $this;
    }

    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments->add($attachment);
            $attachment->setSample($this);
        }
        return $this;
    }

    public function removeAttachment(Attachment $attachment): self
    {
        if ($this->attachments->removeElement($attachment) && $attachment->getSample() === $this) {
            $attachment->setSample(null);
        }
        return $this;
    }

    public function getImageCommands(): Collection
    {
        return $this->imageCommands;
    }

    public function addImageCommand(ImageCommand $imageCommand): self
    {
        if (!$this->imageCommands->contains($imageCommand)) {
            $this->imageCommands->add($imageCommand);
            $imageCommand->setSample($this);
        }
        return $this;
    }

    public function removeImageCommand(ImageCommand $imageCommand): self
    {
        if ($this->imageCommands->removeElement($imageCommand) && $imageCommand->getSample() === $this) {
            $imageCommand->setSample(null);
        }
        return $this;
    }

    public function getDiCommandNumber(): string
    {
        return $this->diCommandNumber;
    }

    public function setDiCommandNumber(string $diCommandNumber): self
    {
        $this->diCommandNumber = $diCommandNumber;
        return $this;
    }

    public function getDimension(): Dimension
    {
        return $this->dimension;
    }

    public function setDimension(Dimension $dimension): self
    {
        $this->dimension = $dimension;
        return $this;
    }

    // Convenience methods to access width and height directly
    public function getWidth(): string
    {
        return $this->dimension->getWidth();
    }

    public function setWidth(string $width): self
    {
        $this->dimension = new Dimension($width, $this->dimension->getHeight());
        return $this;
    }

    public function getHeight(): string
    {
        return $this->dimension->getHeight();
    }

    public function setHeight(string $height): self
    {
        $this->dimension = new Dimension($this->dimension->getWidth(), $height);
        return $this;
    }

    public function getRn(): ?string
    {
        return $this->rn;
    }

    public function setRn(?string $rn): self
    {
        $this->rn = $rn;
        return $this;
    }

    public function getTransmissionDate(): ?DateTimeInterface
    {
        return $this->transmissionDate;
    }

    public function setTransmissionDate(?DateTimeInterface $transmissionDate): self
    {
        $this->transmissionDate = $transmissionDate;
        return $this;
    }

    public function getCustomerComment(): ?string
    {
        return $this->customerComment;
    }

    public function setCustomerComment(?string $customerComment): self
    {
        $this->customerComment = $customerComment;
        return $this;
    }

    // Added getters for createdAt and updatedAt
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'carpetDesignOrder' => $this->carpetDesignOrder?->getId(),
            'location' => $this->location?->getId(),
            'collection' => $this->collection?->getId(),
            'model' => $this->model?->getId(),
            'status' => $this->status?->getId(),
            'quality' => $this->quality?->getId(),
            'images' => $this->images->map(fn(Image $image) => $image->toArray())->toArray(),
            'attachments' => $this->attachments->map(fn(Attachment $attachment) => $attachment->toArray())->toArray(),
            'diCommandNumber' => $this->diCommandNumber,
            'width' => $this->dimension->getWidth(),
            'height' => $this->dimension->getHeight(),
            'rn' => $this->rn,
            'transmissionDate' => $this->transmissionDate?->format(DateTimeInterface::ATOM),
            'customerComment' => $this->customerComment,
            'createdAt' => $this->createdAt->format(DateTimeInterface::ATOM),
            'updatedAt' => $this->updatedAt?->format(DateTimeInterface::ATOM),
        ];
    }
}
