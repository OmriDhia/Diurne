<?php

namespace App\Contremarque\Bus\Command\CarpetComposition\Thread;

use App\Common\Bus\Command\Command;

class UpdateThreadCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly int $threadNumber,
        public readonly int $techColorId,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getThreadNumber(): int
    {
        return $this->threadNumber;
    }

    public function getTechColorId(): int
    {
        return $this->techColorId;
    }
}
