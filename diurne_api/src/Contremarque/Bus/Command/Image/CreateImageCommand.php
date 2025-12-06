<?php

namespace App\Contremarque\Bus\Command\Image;

use DateTimeImmutable;
use App\Common\Bus\Command\Command;

class CreateImageCommand implements Command
{
    public function __construct(
        private readonly string $image_reference,
        private readonly int $carpetDesignOrderId,
        private readonly int $imageTypeId,
        private readonly bool $isValidated,
        private readonly ?bool $hasError,
        private readonly ?string $error,
        private readonly ?string $commentaire,
        private readonly DateTimeImmutable $validatedAt
    ) {
    }

    public function getImageReference(): string
    {
        return $this->image_reference;
    }

    public function isValidated(): bool
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

    public function getCarpetDesignOrderId(): ?int
    {
        return $this->carpetDesignOrderId;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function getValidatedAt(): DateTimeImmutable
    {
        return $this->validatedAt;
    }

    public function getImageTypeId(): ?int
    {
        return $this->imageTypeId;
    }
}
