<?php

namespace App\Contremarque\Bus\Command\CarpetComposition\Thread;

use App\Common\Bus\Command\Command;

class CreateThreadCommand implements Command
{
    public function __construct(
        public readonly int $carpetCompositionId,
        public readonly int $threadNumber,
        public readonly int $techColorId,
    ) {
    }

    public function getCarpetCompositionId(): int
    {
        return $this->carpetCompositionId;
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
