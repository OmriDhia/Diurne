<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\StockExit\CreateStockExit;

use App\Common\Bus\Command\CommandHandler;
use App\MobileAppApi\Entity\StockExit;
use App\MobileAppApi\Entity\RN;
use App\MobileAppApi\Entity\UserMobileApp;
use App\MobileAppApi\Repository\StockExitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Common\Bus\Command\CommandResponse;

#[AsMessageHandler]
final class CreateStockExitHandler implements CommandHandler
{
    public function __construct(
        private readonly StockExitRepository $repository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CreateStockExitCommand $command): CommandResponse
    {
        $rn = $this->entityManager->getRepository(RN::class)->find($command->rnId);
        if (!$rn) {
            throw new NotFoundHttpException('RN not found');
        }

        $stockExit = new StockExit();
        $stockExit->setRn($rn);
        $stockExit->setLocation($command->location);
        
        if ($command->userId) {
            $user = $this->entityManager->getReference(UserMobileApp::class, $command->userId);
            $stockExit->setUser($user);
        }

        $this->repository->save($stockExit, true);

        return new CreateStockExitResponse($stockExit);
    }
}
