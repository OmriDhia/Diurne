<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\AttachCarpetDesignOrder;

use App\Common\Bus\Command\Command;

class AttachCarpetDesignOrderCommand implements Command
{
    public function __construct(
        public readonly int $carpetDesignOrderId,
        public readonly int $quoteDetailId
    ) {}
}
