<?php

namespace App\CheckingList\Bus\Command\UpdateQualityRespect;

use App\CheckingList\Repository\QualityRespectRepository;
use App\Common\Bus\Command\CommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class UpdateQualityRespectHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QualityRespectRepository $repository,
    ) {
    }

    public function __invoke(UpdateQualityRespectCommand $command): UpdateQualityRespectResponse
    {
        $respect = $this->repository->find($command->id);
        if (!$respect) {
            throw new EntityNotFoundException('QualityRespect not found');
        }

        // Respect Plan fields
        if (null !== $command->respectPlanRelevant) {
            $respect->setRespectPlanRelevant($command->respectPlanRelevant);
        }
        if (null !== $command->respectPlanValid) {
            $respect->setRespectPlanValid($command->respectPlanValid);
        }
        if (null !== $command->respectPlanSeen) {
            $respect->setRespectPlanSeen($command->respectPlanSeen);
        }
        if (null !== $command->respectPlanComment) {
            $respect->setRespectPlanComment($command->respectPlanComment);
        }

        // Respect Door Height fields
        if (null !== $command->respectDoorHeightRelevant) {
            $respect->setRespectDoorHeightRelevant($command->respectDoorHeightRelevant);
        }
        if (null !== $command->respectDoorHeightValid) {
            $respect->setRespectDoorHeightValid($command->respectDoorHeightValid);
        }
        if (null !== $command->respectDoorHeightSeen) {
            $respect->setRespectDoorHeightSeen($command->respectDoorHeightSeen);
        }
        if (null !== $command->respectDoorHeightComment) {
            $respect->setRespectDoorHeightComment($command->respectDoorHeightComment);
        }

        // Respect Max Min Length fields
        if (null !== $command->respectMaxMinLengthRelevant) {
            $respect->setRespectMaxMinLengthRelevant($command->respectMaxMinLengthRelevant);
        }
        if (null !== $command->respectMaxMinLengthValid) {
            $respect->setRespectMaxMinLengthValid($command->respectMaxMinLengthValid);
        }
        if (null !== $command->respectMaxMinLengthSeen) {
            $respect->setRespectMaxMinLengthSeen($command->respectMaxMinLengthSeen);
        }

        // Respect Max Min Width fields
        if (null !== $command->respectMaxMinWidthRelevant) {
            $respect->setRespectMaxMinWidthRelevant($command->respectMaxMinWidthRelevant);
        }
        if (null !== $command->respectMaxMinWidthValid) {
            $respect->setRespectMaxMinWidthValid($command->respectMaxMinWidthValid);
        }
        if (null !== $command->respectMaxMinWidthSeen) {
            $respect->setRespectMaxMinWidthSeen($command->respectMaxMinWidthSeen);
        }

        // Wall Distance Right fields
        if (null !== $command->respectwallDistanceRightRelevant) {
            $respect->setRespectwallDistanceRightRelevant($command->respectwallDistanceRightRelevant);
        }
        if (null !== $command->respectwallDistanceRightValid) {
            $respect->setRespectwallDistanceRightValid($command->respectwallDistanceRightValid);
        }
        if (null !== $command->respectwallDistanceRightSeen) {
            $respect->setRespectwallDistanceRightSeen($command->respectwallDistanceRightSeen);
        }

        // Wall Distance Left fields
        if (null !== $command->respectwallDistanceLeftRelevant) {
            $respect->setRespectwallDistanceLeftRelevant($command->respectwallDistanceLeftRelevant);
        }
        if (null !== $command->respectwallDistanceLeftValid) {
            $respect->setRespectwallDistanceLeftValid($command->respectwallDistanceLeftValid);
        }
        if (null !== $command->respectwallDistanceLeftSeen) {
            $respect->setRespectwallDistanceLeftSeen($command->respectwallDistanceLeftSeen);
        }

        // Respect Foss fields
        if (null !== $command->respectFossRelevant) {
            $respect->setRespectFossRelevant($command->respectFossRelevant);
        }
        if (null !== $command->respectFossValide) {
            $respect->setRespectFossValide($command->respectFossValide);
        }
        if (null !== $command->respectFossSeen) {
            $respect->setRespectFossSeen($command->respectFossSeen);
        }
        if (null !== $command->respectFossComment) {
            $respect->setRespectFossComment($command->respectFossComment);
        }

        // Respect Other Carpet fields
        if (null !== $command->respectOtherCarpetRelevant) {
            $respect->setRespectOtherCarpetRelevant($command->respectOtherCarpetRelevant);
        }
        if (null !== $command->respectOtherCarpetValid) {
            $respect->setRespectOtherCarpetValid($command->respectOtherCarpetValid);
        }
        if (null !== $command->respectOtherCarpetSeen) {
            $respect->setRespectOtherCarpetSeen($command->respectOtherCarpetSeen);
        }
        if (null !== $command->respectOtherCarpetComment) {
            $respect->setRespectOtherCarpetComment($command->respectOtherCarpetComment);
        }

        // Respect Color fields
        if (null !== $command->respectColorRelevant) {
            $respect->setRespectColorRelevant($command->respectColorRelevant);
        }
        if (null !== $command->respectColorValid) {
            $respect->setRespectColorValid($command->respectColorValid);
        }
        if (null !== $command->respectColorSeen) {
            $respect->setRespectColorSeen($command->respectColorSeen);
        }
        if (null !== $command->respectColorComment) {
            $respect->setRespectColorComment($command->respectColorComment);
        }

        // Respect Material fields
        if (null !== $command->respectMaterialRelevant) {
            $respect->setRespectMaterialRelevant($command->respectMaterialRelevant);
        }
        if (null !== $command->respectMaterialValid) {
            $respect->setRespectMaterialValid($command->respectMaterialValid);
        }
        if (null !== $command->respectMaterialSeen) {
            $respect->setRespectMaterialSeen($command->respectMaterialSeen);
        }
        if (null !== $command->respectMaterialComment) {
            $respect->setRespectMaterialComment($command->respectMaterialComment);
        }

        // Respect Remark fields
        if (null !== $command->respectRemarkRelevant) {
            $respect->setRespectRemarkRelevant($command->respectRemarkRelevant);
        }
        if (null !== $command->respectRemarkValid) {
            $respect->setRespectRemarkValid($command->respectRemarkValid);
        }
        if (null !== $command->respectRemarkSeen) {
            $respect->setRespectRemarkSeen($command->respectRemarkSeen);
        }
        if (null !== $command->respectRemarkComment) {
            $respect->setRespectRemarkComment($command->respectRemarkComment);
        }

        // Respect Velour fields
        if (null !== $command->respectVelourRelevant) {
            $respect->setRespectVelourRelevant($command->respectVelourRelevant);
        }
        if (null !== $command->respectVelourValid) {
            $respect->setRespectVelourValid($command->respectVelourValid);
        }
        if (null !== $command->respectVelourSeen) {
            $respect->setRespectVelourSeen($command->respectVelourSeen);
        }
        if (null !== $command->respectVelourComment) {
            $respect->setRespectVelourComment($command->respectVelourComment);
        }

        // Wall Distance Top fields
        if (null !== $command->wallDistanceTopRelevant) {
            $respect->setWallDistanceTopRelevant($command->wallDistanceTopRelevant);
        }
        if (null !== $command->wallDistanceTopValid) {
            $respect->setWallDistanceTopValid($command->wallDistanceTopValid);
        }
        if (null !== $command->wallDistanceTopSeen) {
            $respect->setWallDistanceTopSeen($command->wallDistanceTopSeen);
        }

        // Wall Distance Bottom fields
        if (null !== $command->wallDistanceBottomRelevant) {
            $respect->setWallDistanceBottomRelevant($command->wallDistanceBottomRelevant);
        }
        if (null !== $command->wallDistanceBottomValid) {
            $respect->setWallDistanceBottomValid($command->wallDistanceBottomValid);
        }
        if (null !== $command->wallDistanceBottomSeen) {
            $respect->setWallDistanceBottomSeen($command->wallDistanceBottomSeen);
        }

        // Other fields
        if (null !== $command->penalty) {
            $respect->setPenalty($command->penalty);
        }
        
        if (null !== $command->orderStatus) {
            $respect->setOrderStatus($command->orderStatus);
        }

        $this->entityManager->flush();

        return new UpdateQualityRespectResponse($respect);
    }
}
