<?php

namespace App\Contremarque\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CarpetReference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'carpetReferences', cascade: ['remove'])]
    private ?Contremarque $contremarque = null;

    #[ORM\Column(length: 10)]
    private ?int $sequenceNumber = null;

    #[ORM\Column(length: 50)]
    private ?string $reference = null;

    #[ORM\ManyToOne(inversedBy: 'carpetReference')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContremarque(): ?Contremarque
    {
        return $this->contremarque;
    }

    public function setContremarque(?Contremarque $contremarque): static
    {
        $this->contremarque = $contremarque;

        return $this;
    }

    public function getSequenceNumber(): ?int
    {
        return $this->sequenceNumber;
    }

    public function setSequenceNumber(int $sequenceNumber): static
    {
        $this->sequenceNumber = $sequenceNumber;
        $this->updateReference();

        return $this;
    }

    private function updateReference(): void
    {
        if (null !== $this->contremarque && null !== $this->sequenceNumber) {
            $this->reference = $this->contremarque->getProjectNumber().' T '.str_pad((string) $this->sequenceNumber, 3, '0', STR_PAD_LEFT);
        }
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

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }
}
