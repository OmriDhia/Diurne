<?php

namespace App\Contremarque\Entity\ImageCommand;

use DateTimeInterface;
use App\Contremarque\Entity\Attachment;
use App\Setting\Entity\ImageType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class TechnicalImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ImageCommand::class, inversedBy: 'technicalImages')]
    private ?ImageCommand $imageCommand = null;

    #[ORM\ManyToOne(targetEntity: ImageType::class)]
    private ?ImageType $imageType = null;
    #[ORM\OneToOne(targetEntity: Attachment::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(name: "attachment_id", referencedColumnName: "id", nullable: true, onDelete: "CASCADE")]
    private ?Attachment $attachment = null;
    #[ORM\Column(type: Types::STRING)]
    private string $name;
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $updatedAt;

    public function __construct()
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'imageType' => $this->imageType->getName(),
            'imageTypeId' => $this->imageType->getId(),
            'attachment' => $this->attachment->toArray(),
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAttachment(): ?Attachment
    {
        return $this->attachment;
    }

    public function setAttachment(?Attachment $attachment): void
    {
        $this->attachment = $attachment;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getImageCommand(): ?ImageCommand
    {
        return $this->imageCommand;
    }

    public function setImageCommand(?ImageCommand $imageCommand): void
    {
        $this->imageCommand = $imageCommand;
    }

    public function getImageType(): ?ImageType
    {
        return $this->imageType;
    }

    public function setImageType(?ImageType $imageType): void
    {
        $this->imageType = $imageType;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
