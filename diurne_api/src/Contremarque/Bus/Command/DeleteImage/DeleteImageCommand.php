<?php

namespace App\Contremarque\Bus\Command\DeleteImage;

use App\Common\Bus\Command\Command;

class DeleteImageCommand implements Command
{
    public function __construct(
        private readonly array $ids
    )
    {
    }

    public function getIds(): array
    {
        return $this->ids;
    }
}