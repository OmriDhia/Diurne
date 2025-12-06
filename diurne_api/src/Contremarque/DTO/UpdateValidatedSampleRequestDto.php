<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateValidatedSampleRequestDto
{
    #[Assert\Length(max: 128)]
    public ?string $rnValidatedSample = null;

    #[Assert\Type('bool')]
    public ?bool $color = null;

    #[Assert\Length(max: 128)]
    public ?string $libColor = null;

    #[Assert\Type('bool')]
    public ?bool $velvet = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 128)]
    public string $libVelvet = '';

    #[Assert\Type('bool')]
    public ?bool $material = null;

    #[Assert\Length(max: 128)]
    public ?string $libMaterial = null;

    #[Assert\Length(max: 255)]
    public ?string $customerNoteOnSample = null;

    // Add more fields as necessary

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

    public function setColor(?bool $color): static
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

    public function setVelvet(?bool $velvet): static
    {
        $this->velvet = $velvet;

        return $this;
    }

    public function getLibVelvet(): string
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

    public function setMaterial(?bool $material): static
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
}
