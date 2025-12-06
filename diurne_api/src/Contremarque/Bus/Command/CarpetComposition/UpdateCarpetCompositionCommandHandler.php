<?php

namespace App\Contremarque\Bus\Command\CarpetComposition;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\CarpetCompositionRepository;
use Doctrine\ORM\Exception\ORMException;

class UpdateCarpetCompositionCommandHandler implements CommandHandler
{
    public function __construct(private readonly CarpetCompositionRepository $carpetCompositionRepository)
    {
    }

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateCarpetCompositionCommand $command): CarpetCompositionResponse
    {
        $carpetComposition = $this->carpetCompositionRepository->find($command->getId());

        if (null === $carpetComposition) {
            throw new ResourceNotFoundException();
        }
        if (null !== $command->getTrame()) {
            $carpetComposition->setTrame($command->getTrame());
        }
        if (null !== $command->getThreadCount()) {
            $carpetComposition->setThreadCount($command->getThreadCount());
        }
        if (null !== $command->getLayerCount()) {
            $carpetComposition->setLayerCount($command->getLayerCount());
        }

        $this->carpetCompositionRepository->save($carpetComposition, true);

        return new CarpetCompositionResponse($carpetComposition);
    }
}
