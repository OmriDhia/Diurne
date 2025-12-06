<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Commercial;

use App\Common\Bus\Command\Command;

class CommercialAttributionValidationCommand implements Command
{
    public function __construct(
        private readonly int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
