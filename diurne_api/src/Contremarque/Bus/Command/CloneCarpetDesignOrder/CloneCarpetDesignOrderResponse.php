<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CloneCarpetDesignOrder;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\CarpetDesignOrder;

class CloneCarpetDesignOrderResponse implements CommandResponse
{
    public function __construct(private readonly CarpetDesignOrder $order)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->order->getId(),
        ];
    }
}
