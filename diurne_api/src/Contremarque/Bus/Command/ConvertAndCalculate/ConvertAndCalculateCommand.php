<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\ConvertAndCalculate;

use App\Common\Bus\Command\Command;

class ConvertAndCalculateCommand implements Command
{
    public function __construct(
        public ?float $largCm,
        public ?float $lngCm,
        public ?float $largFeet,
        public ?float $lngFeet,
        public ?float $largInches,
        public ?float $lngInches,
        public string $InputUnit,
        public ?int $quoteDetailId,
        public ?float $totalPriceHt,
        public ?int $currencyId
    ) {}
}
