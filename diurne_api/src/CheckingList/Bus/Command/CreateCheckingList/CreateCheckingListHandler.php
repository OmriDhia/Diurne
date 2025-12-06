<?php

namespace App\CheckingList\Bus\Command\CreateCheckingList;

use App\CheckingList\Entity\CheckingList;
use App\CheckingList\Entity\ShapeValidation;
use App\CheckingList\Entity\QualityCheck;
use App\CheckingList\Entity\QualityRespect;
use App\CheckingList\Entity\LayersValidation;
use App\CheckingList\Service\CloneCheckingListService;
use App\CheckingList\Repository\CheckingListRepository;
use App\Workshop\Repository\WorkshopOrderRepository;
use App\User\Repository\UserRepository;
use App\Common\Bus\Command\CommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class CreateCheckingListHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface   $entityManager,
        private readonly CheckingListRepository   $checkingListRepository,
        private readonly WorkshopOrderRepository  $workshopOrderRepository,
        private readonly UserRepository           $userRepository,
        private readonly CloneCheckingListService $cloneService,
    )
    {
    }

    public function __invoke(CreateCheckingListCommand $command): CreateCheckingListResponse
    {
        $workshopOrder = $this->workshopOrderRepository->find($command->workshopOrderId);
        if (!$workshopOrder) {
            throw new EntityNotFoundException('WorkshopOrder not found');
        }

        $author = $this->userRepository->findById($command->authorId);
        if (!$author) {
            throw new EntityNotFoundException('User not found');
        }

        $checkingList = new CheckingList();
        $checkingList->setWorkshopOrder($workshopOrder);
        $checkingList->setAuthor($author);
        $checkingList->setDate($command->date);
        $checkingList->setDateEndProd($command->dateEndProd);
        $checkingList->setComment($command->comment);
        $this->entityManager->persist($checkingList);

        $existing = $this->checkingListRepository->findBy(
            ['workshopOrder' => $workshopOrder],
            ['id' => 'DESC'],
            1
        );
        $previous = $existing[0] ?? null;
        if ($previous) {
            $this->cloneService->cloneFrom($previous, $checkingList);
        } else {
            $info = $workshopOrder->getWorkshopInformation();

            $shapeValidation = new ShapeValidation();
            $shapeValidation->setCheckingList($checkingList);
            $checkingList->setShapeValidation($shapeValidation);
            if ($info) {
                $shapeValidation->setRealWidth((string)$info->getRealWidth());
                $shapeValidation->setRealLength((string)$info->getRealHeight());
                $shapeValidation->setSurface((string)$info->getRealSurface());
            } else {
                $shapeValidation->setRealWidth('0');
                $shapeValidation->setRealLength('0');
                $shapeValidation->setSurface('0');
            }
            $this->entityManager->persist($shapeValidation);

            $qualityCheck = new QualityCheck();
            $qualityCheck->setCheckingList($checkingList);
            $checkingList->setQualityCheck($qualityCheck);
            $this->entityManager->persist($qualityCheck);

            $qualityRespect = new QualityRespect();
            $qualityRespect->setCheckingList($checkingList);
            $checkingList->setQualityRespect($qualityRespect);
            $this->entityManager->persist($qualityRespect);

            $composition = $workshopOrder->getImageCommand()?->getCarpetDesignOrder()?->getCarpetSpecification()?->getCarpetComposition();
            if ($composition) {
                foreach ($composition->getLayers() as $layer) {
                    $layerValidation = new LayersValidation();
                    $layerValidation->setCheckingList($checkingList);
                    $layerValidation->setLayer($layer);
                    $checkingList->addLayersValidation($layerValidation);
                    $this->entityManager->persist($layerValidation);
                }
            }
        }

        $this->entityManager->flush();

        return new CreateCheckingListResponse($checkingList);
    }
}
