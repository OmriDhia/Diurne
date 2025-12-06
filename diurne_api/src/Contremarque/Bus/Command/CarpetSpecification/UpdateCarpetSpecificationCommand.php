<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetSpecification;

use App\Common\Bus\Command\Command;
use App\Contremarque\DTO\UpdateCarpetSpecificationDTO;

class UpdateCarpetSpecificationCommand implements Command
{
    public function __construct(
        public int $carpetDesignOrderId,
        public int $id,
        public UpdateCarpetSpecificationDTO $carpetSpecificationDTO
    ) {
    }
}
