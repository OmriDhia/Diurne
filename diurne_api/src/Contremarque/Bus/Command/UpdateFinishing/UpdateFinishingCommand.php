<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateFinishing;

use App\Common\Bus\Command\Command;

class UpdateFinishingCommand implements Command
{
    public function __construct(
        public int $customerInstructionId,
        public int $id,
        public ?string $fabricColor,
        public ?bool $fringe,
        public ?bool $withoutBanking,
        public ?bool $noBinding,
        public ?bool $mzCarved,
        public ?string $otherCarvedSignature,
        public ?string $standardVelvetHeight,
        public ?string $specialVelvetHeight
    ) {
    }
}
