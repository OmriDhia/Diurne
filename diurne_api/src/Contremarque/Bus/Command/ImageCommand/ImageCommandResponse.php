<?php

namespace App\Contremarque\Bus\Command\ImageCommand;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\ImageCommand\ImageCommand;

class ImageCommandResponse implements CommandResponse
{
    public function __construct(private readonly ImageCommand $imageCommand)
    {
    }

    public function toArray(): array
    {
        return $this->imageCommand->toArray();
    }
}