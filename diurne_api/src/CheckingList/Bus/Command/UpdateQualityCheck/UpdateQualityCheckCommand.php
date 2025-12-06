<?php

namespace App\CheckingList\Bus\Command\UpdateQualityCheck;

use App\Common\Bus\Command\Command;

class UpdateQualityCheckCommand implements Command
{
    public function __construct(
        public readonly int $id,
        
        // Graphic fields
        public readonly ?bool $graphicRelevant = null,
        public readonly ?bool $graphicValidation = null,
        public readonly ?bool $graphicSeen = null,
        public readonly ?string $graphicComment = null,
        
        // Instruction fields
        public readonly ?bool $instructionRelevant = null,
        public readonly ?bool $instructionComplianceValidation = null,
        public readonly ?bool $instructionSeen = null,
        public readonly ?string $instructionComment = null,
        
        // Repair fields
        public readonly ?bool $repairRelevant = null,
        public readonly ?bool $repairRelevantValidation = null,
        public readonly ?bool $repairSeen = null,
        public readonly ?string $repairComment = null,
        
        // Tightness fields
        public readonly ?bool $tightnessRelevant = null,
        public readonly ?bool $tightnessValidation = null,
        public readonly ?bool $tightnessSeen = null,
        public readonly ?string $tightnessComment = null,
        
        // Wool fields
        public readonly ?bool $woolRelevant = null,
        public readonly ?bool $woolQualityValidation = null,
        public readonly ?bool $woolSeen = null,
        public readonly ?string $woolComment = null,
        
        // Silk fields
        public readonly ?bool $silkRelevant = null,
        public readonly ?bool $silkQualityValidation = null,
        public readonly ?bool $silkSeen = null,
        public readonly ?string $silkComment = null,
        
        // Special Shape fields
        public readonly ?bool $specialShapeRelevant = null,
        public readonly ?bool $specialShapeRelevantValidation = null,
        public readonly ?bool $specialShapeSeen = null,
        public readonly ?string $specialShapeComment = null,
        
        // Corps Ondu Coins fields
        public readonly ?bool $corpsOnduCoinsRelevant = null,
        public readonly ?bool $corpsOnduCoinsValidation = null,
        public readonly ?bool $corpsOnduCoinsSeen = null,
        public readonly ?string $corpsOnduCoinsComment = null,
        
        // Velour Author fields
        public readonly ?bool $velourAuthorRelevant = null,
        public readonly ?bool $velourAuthorValidation = null,
        public readonly ?bool $velourAuthorSeen = null,
        public readonly ?string $velourComment = null,
        
        // Washing fields
        public readonly ?bool $washingRelevant = null,
        public readonly ?bool $washingValidation = null,
        public readonly ?bool $washingSeen = null,
        public readonly ?string $wachingComment = null,
        
        // Cleaning fields
        public readonly ?bool $cleaningRelevant = null,
        public readonly ?bool $cleaningValidation = null,
        public readonly ?bool $cleaningSeen = null,
        public readonly ?string $cleaningComment = null,
        
        // Carving fields
        public readonly ?bool $carvingRelevant = null,
        public readonly ?bool $carvingValidation = null,
        public readonly ?bool $carvingSeen = null,
        public readonly ?string $carvingComment = null,
        
        // Fabric Color fields
        public readonly ?bool $fabricColorRelevant = null,
        public readonly ?bool $fabricColorValidation = null,
        public readonly ?bool $fabricColorSeen = null,
        public readonly ?string $fabricColorComment = null,
        
        // Frange fields
        public readonly ?bool $frangeRelevant = null,
        public readonly ?bool $frangeValidation = null,
        public readonly ?bool $frangeSeen = null,
        public readonly ?string $frangComment = null,
        
        // No Binding fields
        public readonly ?bool $noBindingRelevant = null,
        public readonly ?bool $noBindingValidation = null,
        public readonly ?bool $noBindingSeen = null,
        public readonly ?string $noBindingComment = null,
        
        // Signature fields
        public readonly ?bool $signatureRelevant = null,
        public readonly ?bool $signatureValidation = null,
        public readonly ?bool $signatureSeen = null,
        public readonly ?string $signatureComment = null,
        
        // Without Backing fields
        public readonly ?bool $withoutBackingRelevant = null,
        public readonly ?bool $withoutBackingValidation = null,
        public readonly ?bool $withoutBackingSeen = null,
        public readonly ?string $withoutBackingComment = null,
        
        public readonly ?string $comment = null,
    ) {
    }
}
