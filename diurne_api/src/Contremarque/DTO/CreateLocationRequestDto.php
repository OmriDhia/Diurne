<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateLocationRequestDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Contremarque cannot be empty.')]
        #[Assert\Positive(message: 'Contremarque must be a positive integer.')]
        public int $contremarqueId,

        #[Assert\NotBlank(message: 'carpetType cannot be empty.')]
        #[Assert\Positive(message: 'CarpetType must be a positive integer.')]
        public int $carpetTypeId,

        public ?string $description,

        // #[Assert\DateTime(format: 'Y-m-d H:i:s', message: 'Quote processing date must be a valid date in Y-m-d H:i:s format.')]
        // public ?string $quote_processing_date,

        #[Assert\PositiveOrZero(message: 'Price min must be zero or positive.')]
        public ?float $price_min,

        #[Assert\PositiveOrZero(message: 'Price max must be zero or positive.')]
        public ?float $price_max,
        // #[Assert\DateTime(format: 'Y-m-d H:i:s', message: 'Created at must be a valid date in Y-m-d H:i:s format.')]
        // public ?string $createdAt,
        #[Assert\Type('bool', message: 'Quote processed must be a boolean value.')]
        public ?bool $quote_processed = false
    ) {}
}
