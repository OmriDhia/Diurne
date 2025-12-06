<?php

namespace App\CheckingList\DTO\ShapeValidation;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateShapeValidationRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\Positive]
        public readonly int $checking_list_id,
        public ?bool $shape_validation = null,
        #[Assert\NotBlank]
        public string $real_width,
        #[Assert\NotBlank]
        public string $real_length,
        #[Assert\NotBlank]
        public string $surface,
        public ?string $diagonal_a = null,
        public ?string $diagonal_b = null,
        public ?string $comment = null,
    ) {
    }
}
