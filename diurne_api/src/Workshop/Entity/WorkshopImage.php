<?php

namespace App\Workshop\Entity;

use App\Contremarque\Entity\Attachment;
use App\Contremarque\Entity\Location;
use App\Setting\Entity\Material;
use App\Workshop\Repository\WorkshopImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class WorkshopImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $file_name = null;

    #[ORM\Column]
    private ?int $id_image_type = null;

    #[ORM\Column(length: 50)]
    private ?string $format = null;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'workshopImages')]
    #[ORM\JoinColumn(name: 'location_id', nullable: false)]
    private ?Location $locationId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;
    #[ORM\ManyToOne(targetEntity: WorkshopOrder::class, inversedBy: 'workshopImages')]
    #[ORM\JoinColumn(name: 'workshop_order_id', nullable: false)]
    private ?WorkshopOrder $workshopOrder = null;
    #[ORM\ManyToOne(targetEntity: Attachment::class, inversedBy: 'workshopImages')]
    #[ORM\JoinColumn(name: 'id_attachment', nullable: false)]
    private ?Attachment $attachmentId = null;

    public function getWorkshopOrder(): ?WorkshopOrder
    {
        return $this->workshopOrder;
    }

    public function setWorkshopOrder(?WorkshopOrder $workshopOrder): void
    {
        $this->workshopOrder = $workshopOrder;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): static
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getIdImageType(): ?int
    {
        return $this->id_image_type;
    }

    public function setIdImageType(int $id_image_type): static
    {
        $this->id_image_type = $id_image_type;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getLocationId(): Location
    {
        return $this->locationId;
    }

    public function setLocationId(?Location $locationId): void
    {
        $this->locationId = $locationId;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAttachmentId(): ?Attachment
    {
        return $this->attachmentId;
    }

    public function setAttachmentId(?Attachment $attachmentId): void
    {
        $this->attachmentId = $attachmentId;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'file_name' => $this->getFileName(),
            'id_image_type' => $this->getIdImageType(),
            'format' => $this->getFormat(),
            'location_id' => $this->getLocationId()->getId(),
            'attachment' => $this->getAttachmentId()->toArray(),
            'workshop_order_id' => $this->getWorkshopOrder()->getId(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->getUpdatedAt()->format('Y-m-d H:i:s'),

        ];
    }

}
