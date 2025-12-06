<?php

namespace App\Contremarque\Bus\Command\CarpetComposition\Thread;

use App\Common\Bus\Command\CommandResponse;

class DeleteThreadResponse implements CommandResponse
{
    public function __construct(private readonly int $threadId)
    {
    }

    public function getThreadId(): int
    {
        return $this->threadId;
    }

    public function toArray(): array
    {
        return [
            'message' => 'Thread deleted successfully',
            'threadId' => $this->getThreadId(),
        ];
    }
}
