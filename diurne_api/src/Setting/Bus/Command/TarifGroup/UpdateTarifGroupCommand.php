<?php

namespace App\Setting\Bus\Command\TarifGroup;

use App\Common\Bus\Command\Command;

class UpdateTarifGroupCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly string $year
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getYear(): string
    {
        return $this->year;
    }

}
