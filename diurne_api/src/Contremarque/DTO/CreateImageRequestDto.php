<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class CreateImageRequestDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Image reference cannot be empty.')]
        #[Assert\Length(max: 50, maxMessage: 'Image reference cannot exceed {{ limit }} characters.')]
        public string $image_reference,

        #[Assert\NotNull(message: 'Validation status must be provided.')]
        public bool $isValidated,
        #[Assert\NotNull(message: 'Validation date must be provided.')]
        public DateTimeImmutable $validatedAt,

        public ?bool $hasError = null,
        public ?int $carpetDesignOrderId = 0,
        public ?int $imageTypeId = 0,

        #[Assert\Length(max: 255, maxMessage: 'Error description cannot exceed {{ limit }} characters.')]
        public ?string $error = null,

        #[Assert\Length(max: 255, maxMessage: 'Comment cannot exceed {{ limit }} characters.')]
        public ?string $commentaire = null
    ) {
    }
}
