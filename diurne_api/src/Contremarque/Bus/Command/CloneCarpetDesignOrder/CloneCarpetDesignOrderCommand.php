<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CloneCarpetDesignOrder;

use App\Common\Bus\Command\Command;

class CloneCarpetDesignOrderCommand implements Command
{
    public function __construct(private readonly int $carpetDesignOrderId)
    {
    }

    public function getCarpetDesignOrderId(): int
    {
        return $this->carpetDesignOrderId;
    }

}
