<?php

namespace App\Contremarque\Bus\Query\GetImageCommandById;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\ImageCommand\ImageCommand;

class GetImageCommandByIdResponse implements QueryResponse
{
    public function __construct(private readonly ImageCommand $imageCommand) {}

    public function toArray(): array
    {
        return $this->imageCommand->toArray();
    }
}
