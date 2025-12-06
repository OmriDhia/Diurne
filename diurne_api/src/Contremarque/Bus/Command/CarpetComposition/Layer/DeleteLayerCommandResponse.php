<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetComposition\Layer;

use App\Common\Bus\Command\CommandResponse;

class DeleteLayerCommandResponse implements CommandResponse
{
    public function __construct(
        private readonly array $deletedLayerIds,
        private readonly string $message
    ) {}

    public function getDeletedLayerIds(): array
    {
        return $this->deletedLayerIds;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function toArray(): array
    {
        return [
            'deletedLayerIds' => $this->deletedLayerIds,
            'message' => $this->message,
        ];
    }
}
