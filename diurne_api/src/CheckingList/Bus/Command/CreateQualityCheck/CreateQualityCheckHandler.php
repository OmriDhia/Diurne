<?php

namespace App\CheckingList\Bus\Command\CreateQualityCheck;

use App\CheckingList\Entity\QualityCheck;
use App\CheckingList\Repository\QualityCheckRepository;
use App\CheckingList\Repository\CheckingListRepository;
use App\Common\Bus\Command\CommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class CreateQualityCheckHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QualityCheckRepository $qualityCheckRepository,
        private readonly CheckingListRepository $checkingListRepository,
    ) {
    }

    public function __invoke(CreateQualityCheckCommand $command): CreateQualityCheckResponse
    {
        $checkingList = $this->checkingListRepository->find($command->checkingListId);
        if (!$checkingList) {
            throw new EntityNotFoundException('CheckingList not found');
        }

        $qualityCheck = new QualityCheck();
        $qualityCheck->setCheckingList($checkingList);
        $qualityCheck->setGraphicValidation($command->graphicValidation);
        $qualityCheck->setGraphicComment($command->graphicComment);
        $qualityCheck->setInstructionComplianceValidation($command->instructionComplianceValidation);
        $qualityCheck->setInstructionComment($command->instructionComment);
        $qualityCheck->setRepairRelevantValidation($command->repairRelevantValidation);
        $qualityCheck->setRepairComment($command->repairComment);
        $qualityCheck->setTightnessValidation($command->tightnessValidation);
        $qualityCheck->setTightnessComment($command->tightnessComment);
        $qualityCheck->setWoolQualityValidation($command->woolQualityValidation);
        $qualityCheck->setWoolComment($command->woolComment);
        $qualityCheck->setSilkQualityValidation($command->silkQualityValidation);
        $qualityCheck->setSilkComment($command->silkComment);
        $qualityCheck->setSpecialShapeRelevantValidation($command->specialShapeRelevantValidation);
        $qualityCheck->setSpecialShapeComment($command->specialShapeComment);
        $qualityCheck->setCorpsOnduCoinsValidation($command->corpsOnduCoinsValidation);
        $qualityCheck->setCorpsOnduCoinsComment($command->corpsOnduCoinsComment);
        $qualityCheck->setVelourAuthorValidation($command->velourAuthorValidation);
        $qualityCheck->setVelourComment($command->velourComment);
        $qualityCheck->setWashingValidation($command->washingValidation);
        $qualityCheck->setWachingComment($command->wachingComment);
        $qualityCheck->setCleaningValidation($command->cleaningValidation);
        $qualityCheck->setCleaningComment($command->cleaningComment);
        $qualityCheck->setCarvingValidation($command->carvingValidation);
        $qualityCheck->setCarvingComment($command->carvingComment);
        $qualityCheck->setFabricColorValidation($command->fabricColorValidation);
        $qualityCheck->setFabricColorComment($command->fabricColorComment);
        $qualityCheck->setFrangeValidation($command->frangeValidation);
        $qualityCheck->setFrangComment($command->frangComment);
        $qualityCheck->setNoBindingValidation($command->noBindingValidation);
        $qualityCheck->setNoBindingComment($command->noBindingComment);
        $qualityCheck->setSignatureValidation($command->signatureValidation);
        $qualityCheck->setSignatureComment($command->signatureComment);
        $qualityCheck->setWithoutBackingValidation($command->withoutBackingValidation);
        $qualityCheck->setWithoutBackingComment($command->withoutBackingComment);
        $qualityCheck->setComment($command->comment);

        $this->entityManager->persist($qualityCheck);
        $this->entityManager->flush();

        return new CreateQualityCheckResponse($qualityCheck);
    }
}
