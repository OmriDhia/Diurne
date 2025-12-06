<?php

namespace App\CheckingList\Bus\Command\CreateQualityRespect;

use App\CheckingList\Entity\QualityRespect;
use App\CheckingList\Repository\QualityRespectRepository;
use App\CheckingList\Repository\CheckingListRepository;
use App\Common\Bus\Command\CommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class CreateQualityRespectHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QualityRespectRepository $repository,
        private readonly CheckingListRepository $checkingListRepository,
    ) {
    }

    public function __invoke(CreateQualityRespectCommand $command): CreateQualityRespectResponse
    {
        $checkingList = $this->checkingListRepository->find($command->checkingListId);
        if (!$checkingList) {
            throw new EntityNotFoundException('CheckingList not found');
        }

        $respect = new QualityRespect();
        $respect->setCheckingList($checkingList);
        $respect->setRespectPlanValid($command->respectPlanValid);
        $respect->setRespectPlanComment($command->respectPlanComment);
        $respect->setRespectDoorHeightValid($command->respectDoorHeightValid);
        $respect->setRespectDoorHeightComment($command->respectDoorHeightComment);
        $respect->setRespectMaxMinLengthValid($command->respectMaxMinLengthValid);
        $respect->setRespectMaxMinWidthValid($command->respectMaxMinWidthValid);
        $respect->setRespectwallDistanceRightValid($command->respectwallDistanceRightValid);
        $respect->setRespectwallDistanceLeftValid($command->respectwallDistanceLeftValid);
        $respect->setPenalty($command->penalty);
        $respect->setRespectFossValide($command->respectFossValide);
        $respect->setRespectFossComment($command->respectFossComment);
        $respect->setRespectOtherCarpetValid($command->respectOtherCarpetValid);
        $respect->setRespectOtherCarpetComment($command->respectOtherCarpetComment);
        $respect->setRespectColorValid($command->respectColorValid);
        $respect->setRespectColorComment($command->respectColorComment);
        $respect->setRespectMaterialValid($command->respectMaterialValid);
        $respect->setRespectMaterialComment($command->respectMaterialComment);
        $respect->setRespectRemarkValid($command->respectRemarkValid);
        $respect->setRespectRemarkComment($command->respectRemarkComment);
        $respect->setRespectVelourValid($command->respectVelourValid);
        $respect->setRespectVelourComment($command->respectVelourComment);
        $respect->setWallDistanceTopValid($command->wallDistanceTopValid);
        $respect->setWallDistanceBottomValid($command->wallDistanceBottomValid);
        $respect->setOrderStatus($command->orderStatus);

        $this->entityManager->persist($respect);
        $this->entityManager->flush();

        return new CreateQualityRespectResponse($respect);
    }
}
