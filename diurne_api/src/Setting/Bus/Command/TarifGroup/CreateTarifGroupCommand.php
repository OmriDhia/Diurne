<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\TarifGroup;

use App\Common\Bus\Command\Command;

class CreateTarifGroupCommand implements Command
{
    public function __construct(
        private readonly string $year
    ) {
    }

    public function getYear(): string
    {
        return $this->year;
    }
}
