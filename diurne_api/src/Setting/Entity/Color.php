<?php

namespace App\Setting\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Color
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $reference = null;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $hexCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getHexCode(): ?string
    {
        return $this->hexCode;
    }

    public function setHexCode(?string $hexCode): static
    {
        $this->hexCode = $hexCode;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'reference' => $this->getReference(),
            'hexCode' => $this->getHexCode(),
        ];
    }
}
