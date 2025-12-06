<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use App\Contremarque\DTO\Assert\NotBlank;
use App\Contremarque\DTO\Assert\Length;
use DateTimeInterface;

class UpdateProjectDiDTO
{
    public function __construct(
        #[NotBlank(message: 'Format cannot be empty.')]
        #[Length(max: 2, maxMessage: 'Format cannot exceed {{ limit }} characters.')]
        public ?string $format,

        public ?DateTimeInterface $deadline,

        public ?bool $transmitted_to_studio,

        public ?DateTimeInterface $transmition_date,

        public ?int $unit_id,
        public ?int $contremarque_id
    ) {
    }
}
