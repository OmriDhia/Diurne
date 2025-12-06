<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\ConvertAndCalculate;

class ConvertAndCalculateResponse
{
    public function __construct(
        private readonly array $dimension,
        private readonly array $price
    ) {
    }

    public function toArray(): array
    {
        return [
            'dimension' => $this->dimension,
            'price' => $this->price,
        ];
    }
}
