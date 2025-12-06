<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Variation;

use App\Common\Bus\Command\CommandResponse;

class ImageVariationResponse implements CommandResponse
{
    public function __construct(
        public ?string $image_reference,
        public ?string $variation,
        public ?array $carpetDesignOrderData
    ) {
    }

    public function toArray(): array
    {
        return [
            'image_reference' => $this->image_reference,
            'variation' => $this->variation,
            'carpetDesignOrder' => $this->carpetDesignOrderData,
        ];
    }
}
