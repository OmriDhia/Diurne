<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCarpetDesignOrderRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        public int                $location_id,

        public ?int               $status_id,
        public ?DateTimeInterface $transmition_date,
        public ?array             $designer_assignments = [],
        public ?string            $modelName = null,
        public ?string            $variation = null,
        public ?bool              $jpeg = null,
        public ?bool              $impression = null,
        public ?bool              $impressionBarreDeLaine = null,

    )
    {
    }
}
