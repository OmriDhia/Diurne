<?php

namespace App\Setting\Bus\Command\Conversion;

use App\Common\Bus\Command\Command;
use App\Setting\DTO\UpdateConversionRequestDto;

class UpdateConversionCommand implements Command
{
    public function __construct(
        public int $conversionId,
        public UpdateConversionRequestDto $dto
    ) {
    }
}
