<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\TriggerStatus;

use App\Common\Bus\Command\CommandResponse;

class TriggerStatusResponse implements CommandResponse
{
    public function __construct(
        private readonly int $id,
        private readonly bool $status
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
        ];
    }
}
