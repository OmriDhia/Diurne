<?php

namespace App\Contremarque\Bus\Command\CreateCarpetDesignOrderVariation;

use App\Common\Bus\Command\Command;

class CreateCarpetDesignOrderVariationCommand implements Command
{
    public function __construct(
        public readonly int $orderId,
        public readonly ?string $variation,
    ) {}

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getVariation(): ?string
    {
        return $this->variation;
    }
}
