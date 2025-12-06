<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateCarpet;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\CarpetOrderDetailRepository;
use App\Contremarque\Repository\ImageCommandRepository;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Setting\Repository\ManufacturerRepository;
use App\Workshop\Entity\Carpet;
use App\Workshop\Repository\CarpetRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateCarpetCommandHandler implements CommandHandler
{
    public function __construct(
        private CarpetRepository            $carpetRepository,
        private EntityManagerInterface      $entityManager,
        private ImageCommandRepository      $imageCommandRepository,
        private ManufacturerRepository      $manufacturerRepository,
        private QuoteDetailRepository       $quoteDetailRepository,
        private CarpetOrderDetailRepository $carpetOrderDetailRepository,
    )
    {
    }

    public function __invoke(CreateCarpetCommand $command): CreateCarpetResponse
    {
        $manufacturer = $this->manufacturerRepository->find($command->manufacturerId);
        if (!$manufacturer) {
            throw new ResourceNotFoundException();
        }
        $imageCommand = null;

        if ($command->imageCommandId !== null) {
            $imageCommand = $this->imageCommandRepository->find($command->imageCommandId);
            $locationId = $imageCommand->getCarpetDesignOrder()->getLocation();
            /* if ($locationId !== null) {
                 $carpetOrderDetail = $this->GetCarpetOrderDetailBylocation($locationId);
             }*/

            if (!$imageCommand) {
                throw new ResourceNotFoundException();
            }
        }
        $carpet = new Carpet();
        $carpet->setRnNumber($this->carpetRepository->getNextRnNumber($command->manufacturerId));
        if ($imageCommand !== null) {
            $carpet->setImageCommand($imageCommand);
        }
        /* if ($carpetOrderDetail !== null) {
             $carpet->setCarpetOrderDetail($carpetOrderDetail);
         }*/
        $this->entityManager->persist($carpet);
        $this->entityManager->flush();

        return new CreateCarpetResponse($carpet);
    }

    /*private function GetCarpetOrderDetailBylocation($location): int
    {
        $quoteDetail = $this->quoteDetailRepository->findOneBy(['location' => $location]);
        dump($quoteDetail->getId());
        if ($quoteDetail) {
            $carpetOrderDetailId = $this->carpetOrderDetailRepository->findOneBy(['quote_detail' => $quoteDetail]);
        }
        dump($carpetOrderDetailId);
        return $carpetOrderDetailId?->getId();

    }*/
}