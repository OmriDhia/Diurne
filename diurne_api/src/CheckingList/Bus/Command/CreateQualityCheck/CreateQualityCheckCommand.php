<?php

namespace App\CheckingList\Bus\Command\CreateQualityCheck;

use App\Common\Bus\Command\Command;

class CreateQualityCheckCommand implements Command
{
    public function __construct(
        public readonly int $checkingListId,
        public readonly ?bool $graphicValidation = null,
        public readonly ?string $graphicComment = null,
        public readonly ?bool $instructionComplianceValidation = null,
        public readonly ?string $instructionComment = null,
        public readonly ?bool $repairRelevantValidation = null,
        public readonly ?string $repairComment = null,
        public readonly ?bool $tightnessValidation = null,
        public readonly ?string $tightnessComment = null,
        public readonly ?bool $woolQualityValidation = null,
        public readonly ?string $woolComment = null,
        public readonly ?bool $silkQualityValidation = null,
        public readonly ?string $silkComment = null,
        public readonly ?bool $specialShapeRelevantValidation = null,
        public readonly ?string $specialShapeComment = null,
        public readonly ?bool $corpsOnduCoinsValidation = null,
        public readonly ?string $corpsOnduCoinsComment = null,
        public readonly ?bool $velourAuthorValidation = null,
        public readonly ?string $velourComment = null,
        public readonly ?bool $washingValidation = null,
        public readonly ?string $wachingComment = null,
        public readonly ?bool $cleaningValidation = null,
        public readonly ?string $cleaningComment = null,
        public readonly ?bool $carvingValidation = null,
        public readonly ?string $carvingComment = null,
        public readonly ?bool $fabricColorValidation = null,
        public readonly ?string $fabricColorComment = null,
        public readonly ?bool $frangeValidation = null,
        public readonly ?string $frangComment = null,
        public readonly ?bool $noBindingValidation = null,
        public readonly ?string $noBindingComment = null,
        public readonly ?bool $signatureValidation = null,
        public readonly ?string $signatureComment = null,
        public readonly ?bool $withoutBackingValidation = null,
        public readonly ?string $withoutBackingComment = null,
        public readonly ?string $comment = null,
    ) {
    }
}
