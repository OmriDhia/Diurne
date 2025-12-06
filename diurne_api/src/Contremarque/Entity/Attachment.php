<?php

namespace App\Contremarque\Entity;

use App\Setting\Entity\AttachmentType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Attachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $file = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $extension = null;

    #[ORM\Column]
    private ?bool $fromDistantServer = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $size = null;

    #[ORM\ManyToOne(inversedBy: 'attachments')]
    private ?AttachmentType $attachmentType = null;

    #[ORM\OneToOne(mappedBy: 'attachment', targetEntity: ImageAttachment::class)]
    private ?ImageAttachment $imageAttachment = null;

    #[ORM\ManyToOne(targetEntity: Sample::class, inversedBy: "attachments")]
    #[ORM\JoinColumn(name: "sample_id", referencedColumnName: "id", nullable: true)]
    private ?Sample $sample = null;

    public function __construct() {}

    public function getSample(): ?Sample
    {
        return $this->sample;
    }

    public function setSample(?Sample $sample): self
    {
        $this->sample = $sample;
        return $this;
    }

    public function getImageAttachment(): ?ImageAttachment
    {
        return $this->imageAttachment;
    }

    public function setImageAttachment(?ImageAttachment $imageAttachment): void
    {
        $this->imageAttachment = $imageAttachment;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'file' => $this->getFile(),
            'path' => $this->getPath(),
            'extension' => $this->getExtension(),
            'fromDistantServer' => $this->isFromDistantServer(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): Attachment
    {
        $this->path = $path;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): static
    {
        $this->extension = $extension;

        return $this;
    }

    public function isFromDistantServer(): ?bool
    {
        return $this->fromDistantServer;
    }


    public function setFromDistantServer(bool $fromDistantServer): static
    {
        $this->fromDistantServer = $fromDistantServer;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getAttachmentType(): ?AttachmentType
    {
        return $this->attachmentType;
    }

    public function setAttachmentType(?AttachmentType $attachmentType): static
    {
        $this->attachmentType = $attachmentType;

        return $this;
    }

    public function __clone()
    {
        $this->id = null;
        $this->imageAttachment = null;
        $this->sample = null;
    }
}
