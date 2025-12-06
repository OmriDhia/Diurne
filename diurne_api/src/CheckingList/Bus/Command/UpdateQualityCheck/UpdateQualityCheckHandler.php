<?php

namespace App\CheckingList\Bus\Command\UpdateQualityCheck;

use App\CheckingList\Repository\QualityCheckRepository;
use App\Common\Bus\Command\CommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class UpdateQualityCheckHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QualityCheckRepository $repository,
    ) {
    }

    public function __invoke(UpdateQualityCheckCommand $command): UpdateQualityCheckResponse
    {
        $qualityCheck = $this->repository->find($command->id);
        if (!$qualityCheck) {
            throw new EntityNotFoundException('QualityCheck not found');
        }

        // Graphic fields
        if (null !== $command->graphicRelevant) {
            $qualityCheck->setGraphicRelevant($command->graphicRelevant);
        }
        if (null !== $command->graphicValidation) {
            $qualityCheck->setGraphicValidation($command->graphicValidation);
        }
        if (null !== $command->graphicSeen) {
            $qualityCheck->setGraphicSeen($command->graphicSeen);
        }
        if (null !== $command->graphicComment) {
            $qualityCheck->setGraphicComment($command->graphicComment);
        }

        // Instruction fields
        if (null !== $command->instructionRelevant) {
            $qualityCheck->setInstructionRelevant($command->instructionRelevant);
        }
        if (null !== $command->instructionComplianceValidation) {
            $qualityCheck->setInstructionComplianceValidation($command->instructionComplianceValidation);
        }
        if (null !== $command->instructionSeen) {
            $qualityCheck->setInstructionSeen($command->instructionSeen);
        }
        if (null !== $command->instructionComment) {
            $qualityCheck->setInstructionComment($command->instructionComment);
        }

        // Repair fields
        if (null !== $command->repairRelevant) {
            $qualityCheck->setRepairRelevant($command->repairRelevant);
        }
        if (null !== $command->repairRelevantValidation) {
            $qualityCheck->setRepairRelevantValidation($command->repairRelevantValidation);
        }
        if (null !== $command->repairSeen) {
            $qualityCheck->setRepairSeen($command->repairSeen);
        }
        if (null !== $command->repairComment) {
            $qualityCheck->setRepairComment($command->repairComment);
        }

        // Tightness fields
        if (null !== $command->tightnessRelevant) {
            $qualityCheck->setTightnessRelevant($command->tightnessRelevant);
        }
        if (null !== $command->tightnessValidation) {
            $qualityCheck->setTightnessValidation($command->tightnessValidation);
        }
        if (null !== $command->tightnessSeen) {
            $qualityCheck->setTightnessSeen($command->tightnessSeen);
        }
        if (null !== $command->tightnessComment) {
            $qualityCheck->setTightnessComment($command->tightnessComment);
        }

        // Wool fields
        if (null !== $command->woolRelevant) {
            $qualityCheck->setWoolRelevant($command->woolRelevant);
        }
        if (null !== $command->woolQualityValidation) {
            $qualityCheck->setWoolQualityValidation($command->woolQualityValidation);
        }
        if (null !== $command->woolSeen) {
            $qualityCheck->setWoolSeen($command->woolSeen);
        }
        if (null !== $command->woolComment) {
            $qualityCheck->setWoolComment($command->woolComment);
        }

        // Silk fields
        if (null !== $command->silkRelevant) {
            $qualityCheck->setSilkRelevant($command->silkRelevant);
        }
        if (null !== $command->silkQualityValidation) {
            $qualityCheck->setSilkQualityValidation($command->silkQualityValidation);
        }
        if (null !== $command->silkSeen) {
            $qualityCheck->setSilkSeen($command->silkSeen);
        }
        if (null !== $command->silkComment) {
            $qualityCheck->setSilkComment($command->silkComment);
        }

        // Special Shape fields
        if (null !== $command->specialShapeRelevant) {
            $qualityCheck->setSpecialShapeRelevant($command->specialShapeRelevant);
        }
        if (null !== $command->specialShapeRelevantValidation) {
            $qualityCheck->setSpecialShapeRelevantValidation($command->specialShapeRelevantValidation);
        }
        if (null !== $command->specialShapeSeen) {
            $qualityCheck->setSpecialShapeSeen($command->specialShapeSeen);
        }
        if (null !== $command->specialShapeComment) {
            $qualityCheck->setSpecialShapeComment($command->specialShapeComment);
        }

        // Corps Ondu Coins fields
        if (null !== $command->corpsOnduCoinsRelevant) {
            $qualityCheck->setCorpsOnduCoinsRelevant($command->corpsOnduCoinsRelevant);
        }
        if (null !== $command->corpsOnduCoinsValidation) {
            $qualityCheck->setCorpsOnduCoinsValidation($command->corpsOnduCoinsValidation);
        }
        if (null !== $command->corpsOnduCoinsSeen) {
            $qualityCheck->setCorpsOnduCoinsSeen($command->corpsOnduCoinsSeen);
        }
        if (null !== $command->corpsOnduCoinsComment) {
            $qualityCheck->setCorpsOnduCoinsComment($command->corpsOnduCoinsComment);
        }

        // Velour Author fields
        if (null !== $command->velourAuthorRelevant) {
            $qualityCheck->setVelourAuthorRelevant($command->velourAuthorRelevant);
        }
        if (null !== $command->velourAuthorValidation) {
            $qualityCheck->setVelourAuthorValidation($command->velourAuthorValidation);
        }
        if (null !== $command->velourAuthorSeen) {
            $qualityCheck->setVelourAuthorSeen($command->velourAuthorSeen);
        }
        if (null !== $command->velourComment) {
            $qualityCheck->setVelourComment($command->velourComment);
        }

        // Washing fields
        if (null !== $command->washingRelevant) {
            $qualityCheck->setWashingRelevant($command->washingRelevant);
        }
        if (null !== $command->washingValidation) {
            $qualityCheck->setWashingValidation($command->washingValidation);
        }
        if (null !== $command->washingSeen) {
            $qualityCheck->setWashingSeen($command->washingSeen);
        }
        if (null !== $command->wachingComment) {
            $qualityCheck->setWachingComment($command->wachingComment);
        }

        // Cleaning fields
        if (null !== $command->cleaningRelevant) {
            $qualityCheck->setCleaningRelevant($command->cleaningRelevant);
        }
        if (null !== $command->cleaningValidation) {
            $qualityCheck->setCleaningValidation($command->cleaningValidation);
        }
        if (null !== $command->cleaningSeen) {
            $qualityCheck->setCleaningSeen($command->cleaningSeen);
        }
        if (null !== $command->cleaningComment) {
            $qualityCheck->setCleaningComment($command->cleaningComment);
        }

        // Carving fields
        if (null !== $command->carvingRelevant) {
            $qualityCheck->setCarvingRelevant($command->carvingRelevant);
        }
        if (null !== $command->carvingValidation) {
            $qualityCheck->setCarvingValidation($command->carvingValidation);
        }
        if (null !== $command->carvingSeen) {
            $qualityCheck->setCarvingSeen($command->carvingSeen);
        }
        if (null !== $command->carvingComment) {
            $qualityCheck->setCarvingComment($command->carvingComment);
        }

        // Fabric Color fields
        if (null !== $command->fabricColorRelevant) {
            $qualityCheck->setFabricColorRelevant($command->fabricColorRelevant);
        }
        if (null !== $command->fabricColorValidation) {
            $qualityCheck->setFabricColorValidation($command->fabricColorValidation);
        }
        if (null !== $command->fabricColorSeen) {
            $qualityCheck->setFabricColorSeen($command->fabricColorSeen);
        }
        if (null !== $command->fabricColorComment) {
            $qualityCheck->setFabricColorComment($command->fabricColorComment);
        }

        // Frange fields
        if (null !== $command->frangeRelevant) {
            $qualityCheck->setFrangeRelevant($command->frangeRelevant);
        }
        if (null !== $command->frangeValidation) {
            $qualityCheck->setFrangeValidation($command->frangeValidation);
        }
        if (null !== $command->frangeSeen) {
            $qualityCheck->setFrangeSeen($command->frangeSeen);
        }
        if (null !== $command->frangComment) {
            $qualityCheck->setFrangComment($command->frangComment);
        }

        // No Binding fields
        if (null !== $command->noBindingRelevant) {
            $qualityCheck->setNoBindingRelevant($command->noBindingRelevant);
        }
        if (null !== $command->noBindingValidation) {
            $qualityCheck->setNoBindingValidation($command->noBindingValidation);
        }
        if (null !== $command->noBindingSeen) {
            $qualityCheck->setNoBindingSeen($command->noBindingSeen);
        }
        if (null !== $command->noBindingComment) {
            $qualityCheck->setNoBindingComment($command->noBindingComment);
        }

        // Signature fields
        if (null !== $command->signatureRelevant) {
            $qualityCheck->setSignatureRelevant($command->signatureRelevant);
        }
        if (null !== $command->signatureValidation) {
            $qualityCheck->setSignatureValidation($command->signatureValidation);
        }
        if (null !== $command->signatureSeen) {
            $qualityCheck->setSignatureSeen($command->signatureSeen);
        }
        if (null !== $command->signatureComment) {
            $qualityCheck->setSignatureComment($command->signatureComment);
        }

        // Without Backing fields
        if (null !== $command->withoutBackingRelevant) {
            $qualityCheck->setWithoutBackingRelevant($command->withoutBackingRelevant);
        }
        if (null !== $command->withoutBackingValidation) {
            $qualityCheck->setWithoutBackingValidation($command->withoutBackingValidation);
        }
        if (null !== $command->withoutBackingSeen) {
            $qualityCheck->setWithoutBackingSeen($command->withoutBackingSeen);
        }
        if (null !== $command->withoutBackingComment) {
            $qualityCheck->setWithoutBackingComment($command->withoutBackingComment);
        }

        if (null !== $command->comment) {
            $qualityCheck->setComment($command->comment);
        }

        $this->entityManager->flush();

        return new UpdateQualityCheckResponse($qualityCheck);
    }
}
