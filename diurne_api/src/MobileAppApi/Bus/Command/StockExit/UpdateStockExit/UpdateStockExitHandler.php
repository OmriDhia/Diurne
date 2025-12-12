<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\StockExit\UpdateStockExit;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Bus\Command\CommandResponse;
use App\Common\Exception\ResourceNotFoundException;
use App\MobileAppApi\Repository\StockExitRepository;
use App\MobileAppApi\Repository\RNRepository;
use App\MobileAppApi\Repository\UserMobileAppRepository;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateStockExitHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly StockExitRepository $stockExitRepository,
        private readonly RNRepository $rnRepository,
        private readonly UserMobileAppRepository $userRepository
    ) {
    }

    public function __invoke(UpdateStockExitCommand $command): CommandResponse
    {
        $stockExit = $this->stockExitRepository->find($command->id);

        if (!$stockExit) {
            throw new ResourceNotFoundException('Stock Exit not found');
        }

        if ($command->rnId !== null) {
            $rn = $this->rnRepository->find($command->rnId);
            if ($rn) {
                $stockExit->setRn($rn);
            }
        }

        if ($command->userId !== null) {
            $user = $this->userRepository->find($command->userId);
            if ($user) {
                $stockExit->setCreatedBy($user);
            }
        }

        if ($command->deliveryNote !== null) {
            $stockExit->setDeliveryNote($command->deliveryNote);
        }

        if ($command->destination !== null) {
            $stockExit->setDestination($command->destination);
        }

        $stockExit->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return new UpdateStockExitResponse($stockExit);
    }
}
