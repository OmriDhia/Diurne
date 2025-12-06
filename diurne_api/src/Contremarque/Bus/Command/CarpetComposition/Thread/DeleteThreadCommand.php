<?php

namespace App\Contremarque\Bus\Command\CarpetComposition\Thread;

use App\Common\Bus\Command\Command;

class DeleteThreadCommand implements Command
{
    public function __construct(
        public readonly int $carpetCompositionId,
        public readonly int $threadId
    ) {
    }

    public function getCarpetCompositionId(): int
    {
        return $this->carpetCompositionId;
    }

    public function getThreadId(): int
    {
        return $this->threadId;
    }
}
