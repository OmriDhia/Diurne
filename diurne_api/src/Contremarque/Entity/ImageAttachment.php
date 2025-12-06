<?php

namespace App\Contremarque\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ImageAttachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'imageAttachment', cascade: ['persist', 'remove'])]
    private ?Image $image = null;

    #[ORM\OneToOne(targetEntity: Attachment::class, inversedBy: 'imageAttachment', cascade: ['persist'],)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Attachment $attachment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
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

    public function __clone()
    {
        $this->id = null;
        $this->image = null;
        if ($this->attachment instanceof Attachment) {
            $this->attachment = clone $this->attachment;
        }
    }
}
