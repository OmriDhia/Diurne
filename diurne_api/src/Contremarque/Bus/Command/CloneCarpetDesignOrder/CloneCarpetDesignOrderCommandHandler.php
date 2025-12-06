<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CloneCarpetDesignOrder;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CarpetStatusRepository;
use App\Contremarque\Service\CarpetDesignOrder\CarpetDesignOrderCloner;

class CloneCarpetDesignOrderCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly CarpetDesignOrderCloner     $cloner,
        private readonly CarpetStatusRepository      $carpetStatusRepository
    )
    {
    }

    public function __invoke(CloneCarpetDesignOrderCommand $command): CloneCarpetDesignOrderResponse
    {
        $original = $this->carpetDesignOrderRepository->find($command->getCarpetDesignOrderId());
        if (!$original instanceof CarpetDesignOrder) {
            throw new ResourceNotFoundException('CarpetDesignOrder not found.');
        }

        $cloned = $this->cloner->clone($original);

        // If original DI was in "Transmis à l'ADV", set cloned DI status to "Fini"
        $originalStatus = $original->getStatus();
        if ($originalStatus !== null) {
            $toAdvStatus = $this->carpetStatusRepository->getStatusByName("Transmis à l'ADV");
            $finishedStatus = $this->carpetStatusRepository->getStatusByName('Fini');
            if ($toAdvStatus !== null && $finishedStatus !== null && $originalStatus === $toAdvStatus) {
                $cloned->setStatus($finishedStatus);
            }
        }

        $this->carpetDesignOrderRepository->persist($cloned);
        $this->carpetDesignOrderRepository->flush();

        return new CloneCarpetDesignOrderResponse($cloned);
    }
}
