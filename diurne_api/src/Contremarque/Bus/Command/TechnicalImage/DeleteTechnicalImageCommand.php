<?php

namespace App\Contremarque\Bus\Command\TechnicalImage;

use App\Common\Bus\Command\Command;

class DeleteTechnicalImageCommand implements Command
{
    public function __construct(
        private readonly int $id
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}