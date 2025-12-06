<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateFinishing;

use App\Common\Bus\Command\Command;

class CreateFinishingCommand implements Command
{
    public function __construct(
        public readonly int $customerInstructionId,
        public readonly ?string $fabricColor,
        public readonly ?bool $fringe,
        public readonly ?bool $withoutBanking,
        public readonly ?bool $noBinding,
        public readonly ?bool $mzCarved,
        public readonly ?string $otherCarvedSignature,
        public readonly ?string $standardVelvetHeight,
        public readonly ?string $specialVelvetHeight
    ) {
    }
}
