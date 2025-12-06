<?php

namespace App\Contremarque\Bus\Command\CarpetComposition;

use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\CarpetComposition;
use App\Contremarque\Repository\CarpetCompositionRepository;
use App\Contremarque\Repository\CarpetSpecificationRepository;

class CreateCarpetCompositionCommandHandler implements CommandHandler
{
    public function __construct(private readonly CarpetCompositionRepository $carpetCompositionRepository, private readonly CarpetSpecificationRepository $carpetSpecificationRepository)
    {
    }

    public function __invoke(CreateCarpetCompositionCommand $command): CarpetCompositionResponse
    {
        $carpetSpecification = $this->carpetSpecificationRepository->find($command->getCarpetSpecificationId());
        if (null === $carpetSpecification) {
            throw new Exception('CarpetSpecification not found');
        }
        $carpetComposition = $this->carpetCompositionRepository->findOneBy(['carpetSpecification' => $carpetSpecification]);
        if (!empty($carpetComposition)) {
            throw new Exception('There is already related composition to this carpet specification');
        }
        $carpetComposition = new CarpetComposition();
        $carpetComposition->setCarpetSpecification($carpetSpecification);
        $carpetComposition->setTrame($command->getTrame());
        $carpetComposition->setThreadCount($command->getThreadCount());
        $carpetComposition->setLayerCount($command->getLayerCount());

        $this->carpetCompositionRepository->save($carpetComposition, true);

        return new CarpetCompositionResponse($carpetComposition);
    }
}
