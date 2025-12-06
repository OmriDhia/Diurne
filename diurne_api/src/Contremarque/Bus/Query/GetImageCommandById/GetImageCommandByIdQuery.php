<?php

namespace App\Contremarque\Bus\Query\GetImageCommandById;

use App\Common\Bus\Query\Query;

class GetImageCommandByIdQuery implements Query
{
    public function __construct(private readonly int $imageCommandId)
    {
    }

    public function getImageCommandId(): int
    {
        return $this->imageCommandId;
    }

}