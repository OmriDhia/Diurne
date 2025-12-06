<?php

namespace App\Contremarque\Bus\Command\CarpetComposition\Thread;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\Thread;

class ThreadResponse implements CommandResponse
{
    public function __construct(private readonly Thread $thread)
    {
    }

    public function getThread(): Thread
    {
        return $this->thread;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->thread->getId(),
            'threadNumber' => $this->thread->getThreadNumber(),
            'techColor' => $this->thread->toArray(),
        ];
    }
}
