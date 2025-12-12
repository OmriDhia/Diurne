<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\Workshop\DeleteWorkshop;

use App\Common\Bus\Command\Command;

final class DeleteWorkshopCommand implements Command
{
    public function __construct(
        public readonly int $id
    ) {
    }
}
