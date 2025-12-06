<?php

namespace App\CheckingList\DTO\LayersValidation;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateLayersValidationRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\Positive]
        public readonly int $checking_list_id,
        public ?string $layer_comment = null,
        public ?bool $layer_validation = null,
    ) {
    }
}
