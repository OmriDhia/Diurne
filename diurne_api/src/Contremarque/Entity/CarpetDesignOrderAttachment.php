<?php

namespace App\Contremarque\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CarpetDesignOrderAttachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Attachment $attachment = null;

    #[ORM\ManyToOne(targetEntity: CarpetDesignOrder::class, inversedBy: 'carpetDesignOrderAttachments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CarpetDesignOrder $carpetDesignOrder = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttachment(): ?Attachment
    {
        return $this->attachment;
    }

    public function setAttachment(?Attachment $attachment): static
    {
        $this->attachment = $attachment;

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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'attachment' => $this->getAttachment() ? $this->getAttachment()->toArray() : null,  // Assuming Attachment entity has a toArray() method
            'carpetDesignOrder' => $this->getCarpetDesignOrder() ? $this->getCarpetDesignOrder()->getId() : null,  // Assuming you want only the ID of the CarpetDesignOrder
        ];
    }
}
