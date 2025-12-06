<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateDesignerAssignmentRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        public int     $designerId,

        #[Assert\NotNull]
        public bool    $inProgress,

        #[Assert\NotNull]
        public bool    $stopped,

        #[Assert\NotNull]
        public bool    $done,

        public ?string $dateFrom,
        public ?string $dateTo
    )
    {
    }
}
