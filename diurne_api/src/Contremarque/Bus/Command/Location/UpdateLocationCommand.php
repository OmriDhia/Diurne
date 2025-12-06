<?php

// src/Contremarque/Bus/Command/Location/UpdateLocationCommand.php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Location;

use App\Common\Bus\Command\Command;

class UpdateLocationCommand implements Command
{
    public function __construct(
        public int     $id,
        public ?int    $carpetTypeId,
        public ?string $description,
        public ?bool   $quoteProcessed,
        public ?string $quoteProcessingDate,
        public ?string $priceMin,
        public ?string $priceMax,
        public ?string $updatedAt,
        public ?int    $contremarqueId
    )
    {
    }

    // Getters can be added here if necessary
}
