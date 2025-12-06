<?php

namespace App\Contremarque\Bus\Command\CreateQuoteCarpetSpecification;

use App\Common\Bus\Command\Command;
use App\Contremarque\DTO\CarpetSpecificationDTO;

class CreateQuoteCarpetSpecificationCommand implements Command
{
    public function __construct(
        public $quoteDetailId,
        public CarpetSpecificationDTO $carpetSpecificationDTO
    ) {
    }
}
