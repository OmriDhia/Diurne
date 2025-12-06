<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateImageVariationRequestDto
{
    public function __construct(
        #[Assert\Length(max: 50, maxMessage: 'Image reference cannot exceed {{ limit }} characters.')]
        public ?string $variation_image_reference,
        public ?string $variation,
    ) {
    }
}
