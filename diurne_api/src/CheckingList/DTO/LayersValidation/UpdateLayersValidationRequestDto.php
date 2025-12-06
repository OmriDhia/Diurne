<?php

namespace App\CheckingList\DTO\LayersValidation;

use App\Common\DTO\BaseDto;

class UpdateLayersValidationRequestDto extends BaseDto
{
    public function __construct(
        public ?string $layer_comment = null,
        public ?bool $layer_validation = null,
    ) {
    }
}
