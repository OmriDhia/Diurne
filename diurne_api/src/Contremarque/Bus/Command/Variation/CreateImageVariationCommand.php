<?php

namespace App\Contremarque\Bus\Command\Variation;

use App\Common\Bus\Command\Command;

class CreateImageVariationCommand implements Command
{
    public function __construct(
        public int $carpetDesignOrderId,
        public ?string $variation_image_reference,
        public ?string $variation
    ) {
    }
}
