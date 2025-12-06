<?php

namespace App\Contremarque\Bus\Command\DeleteCarpetDesignOrder;

use DateTimeImmutable;
use Throwable;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Common\Bus\Command\CommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteCarpetDesignOrderCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly EntityManagerInterface      $entityManager
    )
    {
    }

    public function __invoke(DeleteCarpetDesignOrderCommand $command): DeleteCarpetDesignOrderResponse
    {
        $carpetDesignOrder = $this->carpetDesignOrderRepository->find($command->getId());

        if (!$carpetDesignOrder) {
            throw new NotFoundHttpException("Carpet Design Order with ID {$command->getId()} not found");
        }

        // Set soft delete fields
        $carpetDesignOrder->setDeletedAt(new DateTimeImmutable());
        $carpetDesignOrder->setDeletedBy($command->getUser()->getEmail());
        $this->entityManager->persist($carpetDesignOrder);
        $this->entityManager->flush();

        return new DeleteCarpetDesignOrderResponse($command->getId());
    }
}
