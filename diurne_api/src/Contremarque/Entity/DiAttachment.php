<?php

namespace App\Contremarque\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class DiAttachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    private ?Attachment $attachment = null;

    #[ORM\ManyToOne(targetEntity: ProjectDi::class, inversedBy: 'diAttachments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProjectDi $di = null;

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

    public function getDi(): ?ProjectDi
    {
        return $this->di;
    }

    public function setDi(?ProjectDi $di): static
    {
        $this->di = $di;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'attachment' => $this->getAttachment() ? $this->getAttachment()->toArray() : null, // Include attachment ID
            'di' => $this->getDi() ? $this->getDi()->getId() : null, // Include ProjectDi ID
        ];
    }
}
