<?php

namespace App\Contremarque\Bus\Command\CarpetSpecification;

use App\Common\Bus\Command\Command;
use App\Contremarque\DTO\CarpetSpecificationDTO;

class CreateOrUpdateCarpetSpecificationCommand implements Command
{
    public function __construct(
        public $carpetDesignOrderId,
        public CarpetSpecificationDTO $carpetSpecificationDTO
    ) {
    }
}
