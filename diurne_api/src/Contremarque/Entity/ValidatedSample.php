<?php

namespace App\Contremarque\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ValidatedSample
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $rnValidatedSample = null;

    #[ORM\Column]
    private ?bool $color = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $libColor = null;

    #[ORM\Column]
    private ?bool $velvet = null;

    #[ORM\Column(length: 128)]
    private ?string $libVelvet = null;

    #[ORM\Column]
    private ?bool $material = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $libMaterial = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customerNoteOnSample = null;

    #[ORM\OneToOne(mappedBy: 'validatedSample', cascade: ['remove'])]
    private ?CustomerInstruction $customerInstruction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRnValidatedSample(): ?string
    {
        return $this->rnValidatedSample;
    }

    public function setRnValidatedSample(?string $rnValidatedSample): static
    {
        $this->rnValidatedSample = $rnValidatedSample;

        return $this;
    }

    public function isColor(): ?bool
    {
        return $this->color;
    }

    public function setColor(bool $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getLibColor(): ?string
    {
        return $this->libColor;
    }

    public function setLibColor(?string $libColor): static
    {
        $this->libColor = $libColor;

        return $this;
    }

    public function isVelvet(): ?bool
    {
        return $this->velvet;
    }

    public function setVelvet(bool $velvet): static
    {
        $this->velvet = $velvet;

        return $this;
    }

    public function getLibVelvet(): ?string
    {
        return $this->libVelvet;
    }

    public function setLibVelvet(string $libVelvet): static
    {
        $this->libVelvet = $libVelvet;

        return $this;
    }

    public function isMaterial(): ?bool
    {
        return $this->material;
    }

    public function setMaterial(bool $material): static
    {
        $this->material = $material;

        return $this;
    }

    public function getLibMaterial(): ?string
    {
        return $this->libMaterial;
    }

    public function setLibMaterial(?string $libMaterial): static
    {
        $this->libMaterial = $libMaterial;

        return $this;
    }

    public function getCustomerNoteOnSample(): ?string
    {
        return $this->customerNoteOnSample;
    }

    public function setCustomerNoteOnSample(?string $customerNoteOnSample): static
    {
        $this->customerNoteOnSample = $customerNoteOnSample;

        return $this;
    }

    public function getCustomerInstruction(): ?CustomerInstruction
    {
        return $this->customerInstruction;
    }

    public function setCustomerInstruction(?CustomerInstruction $customerInstruction): static
    {
        // unset the owning side of the relation if necessary
        if (null === $customerInstruction && null !== $this->customerInstruction) {
            $this->customerInstruction->setValidatedSample(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $customerInstruction && $customerInstruction->getValidatedSample() !== $this) {
            $customerInstruction->setValidatedSample($this);
        }

        $this->customerInstruction = $customerInstruction;

        return $this;
    }

    /**
     * Convert the ValidatedSample entity to an array.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'rnValidatedSample' => $this->getRnValidatedSample(),
            'color' => $this->isColor(),
            'libColor' => $this->getLibColor(),
            'velvet' => $this->isVelvet(),
            'libVelvet' => $this->getLibVelvet(),
            'material' => $this->isMaterial(),
            'libMaterial' => $this->getLibMaterial(),
            'customerNoteOnSample' => $this->getCustomerNoteOnSample(),
            'customerInstructionId' => $this->getCustomerInstruction()?->getId(),
        ];
    }
}
