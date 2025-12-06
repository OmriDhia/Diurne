<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateConstraint;

use App\Common\Bus\Command\Command;

class CreateConstraintCommand implements Command
{
    public function __construct(
        public int $customerInstructionId,
        // Required
        public ?bool $transmittedPlan = null,
        public ?string $libTransmittedPlan = null,
        public ?bool $pit = null,
        public ?string $libPit = null,
        public ?bool $lineHeight = null,
        public ?string $libLineHeight = null,
        public ?bool $specialThickness = null,
        public ?string $libSpecialThickness = null,
        public ?bool $otherCarpetInTheRoom = null,
        public ?string $libOtherCarpetInTheRoom = null,
        public ?bool $miniLength = null,
        public ?bool $maxiLength = null,
        public ?bool $miniWidth = null,
        public ?bool $maxiWidth = null,
        public ?string $dstWallHeight = null,
        public ?string $dstWallDown = null,
        public ?string $dstWallLeft = null,
        public ?string $dstWallRight = null
    )
    {
    }
}
