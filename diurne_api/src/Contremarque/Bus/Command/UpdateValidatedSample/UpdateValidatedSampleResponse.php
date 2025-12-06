<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateValidatedSample;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\ValidatedSample;

class UpdateValidatedSampleResponse implements CommandResponse
{
    public function __construct(private readonly ValidatedSample $validatedSample)
    {
    }

    public function getValidatedSample(): ValidatedSample
    {
        return $this->validatedSample;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->validatedSample->getId(),
            'rnValidatedSample' => $this->validatedSample->getRnValidatedSample(),
            'color' => $this->validatedSample->isColor(),
            'libColor' => $this->validatedSample->getLibColor(),
            'velvet' => $this->validatedSample->isVelvet(),
            'libVelvet' => $this->validatedSample->getLibVelvet(),
            'material' => $this->validatedSample->isMaterial(),
            'libMaterial' => $this->validatedSample->getLibMaterial(),
            'customerNoteOnSample' => $this->validatedSample->getCustomerNoteOnSample(),
        ];
    }
}
