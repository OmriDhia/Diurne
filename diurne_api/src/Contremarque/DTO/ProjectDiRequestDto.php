<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectDiRequestDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Format cannot be empty.')]
        #[Assert\Length(max: 2, maxMessage: 'Format cannot exceed {{ limit }} characters.')]
        public string $format,

        public ?DateTimeInterface $deadline,

        public ?bool $transmitted_to_studio,

        public ?DateTimeInterface $transmition_date,

        public ?int $unit_id,
        public ?int $contremarque_id
    ) {
    }
}
