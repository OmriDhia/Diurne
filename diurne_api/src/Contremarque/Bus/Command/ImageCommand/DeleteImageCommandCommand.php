<?php

namespace App\Contremarque\Bus\Command\ImageCommand;

use App\Common\Bus\Command\Command;

class DeleteImageCommandCommand implements Command
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