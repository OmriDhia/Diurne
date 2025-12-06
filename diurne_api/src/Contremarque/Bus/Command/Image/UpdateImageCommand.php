<?php

namespace App\Contremarque\Bus\Command\Image;

use DateTimeImmutable;
use App\Common\Bus\Command\Command;

class UpdateImageCommand implements Command
{
    public function __construct(
        private readonly int $id,
        private readonly ?string $image_reference = null,
        private readonly ?int $carpetDesignOrderId = null,
        private readonly ?int $imageTypeId = null,
        private readonly ?bool $isValidated = null,
        private readonly ?bool $hasError = null,
        private readonly ?string $error = null,
        private readonly ?string $commentaire = null,
        private readonly ?DateTimeImmutable $validatedAt = null
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getImageReference(): ?string
    {
        return $this->image_reference;
    }

    public function isValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function hasError(): ?bool
    {
        return $this->hasError;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function getValidatedAt(): ?DateTimeImmutable
    {
        return $this->validatedAt;
    }

    public function getCarpetDesignOrderId(): ?int
    {
        return $this->carpetDesignOrderId;
    }

    public function getImageTypeId(): ?int
    {
        return $this->imageTypeId;
    }
}
