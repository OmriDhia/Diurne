<?php

namespace App\CheckingList\DTO\ShapeValidation;

use App\Common\DTO\BaseDto;

class UpdateShapeValidationRequestDto extends BaseDto
{
    public function __construct(
        public ?bool $shape_relevant = null,
        public ?bool $shape_validation = null,
        public ?bool $shape_seen = null,
        public ?string $real_width = null,
        public ?string $real_length = null,
        public ?string $surface = null,
        public ?string $diagonal_a = null,
        public ?string $diagonal_b = null,
        public ?string $comment = null,
    ) {
    }
}
