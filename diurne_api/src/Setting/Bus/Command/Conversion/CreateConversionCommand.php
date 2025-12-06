<?php

namespace App\Setting\Bus\Command\Conversion;

use App\Common\Bus\Command\Command;
use App\Setting\DTO\CreateConversionRequestDto;

class CreateConversionCommand implements Command
{
    public function __construct(public CreateConversionRequestDto $dto)
    {
    }
}
