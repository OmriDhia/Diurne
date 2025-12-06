<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateImageRequestDto
{
    public function __construct(
        #[Assert\Length(max: 50, maxMessage: 'Image reference cannot exceed {{ limit }} characters.')]
        public ?string $image_reference = null,

        public ?bool $isValidated = null,

        public ?int $carpetDesignOrderId = 0,
        public ?int $imageTypeId = 0,

        public ?bool $hasError = null,

        #[Assert\Length(max: 255, maxMessage: 'Error description cannot exceed {{ limit }} characters.')]
        public ?string $error = null,

        #[Assert\Length(max: 255, maxMessage: 'Comment cannot exceed {{ limit }} characters.')]
        public ?string $commentaire = null,

        public ?DateTimeImmutable $validatedAt = null
    ) {
    }
}
