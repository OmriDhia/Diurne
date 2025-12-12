<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\RN\DeleteRN;

use App\Common\Bus\Command\Command;

final class DeleteRNCommand implements Command
{
    public function __construct(
        public readonly int $id
    ) {
    }
}
