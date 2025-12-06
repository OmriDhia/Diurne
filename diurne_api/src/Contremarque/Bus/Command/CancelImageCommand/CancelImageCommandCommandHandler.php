<?php

namespace App\Contremarque\Bus\Command\CancelImageCommand;


use App\Contremarque\Repository\ImageCommandRepository;
use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CancelImageCommandCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ImageCommandRepository $imageCommandRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function __invoke(CancelImageCommandCommand $command): CancelCommandResponse
    {
        $imageCommand = $this->imageCommandRepository->find($command->getId());

        if (!$imageCommand) {
            throw new NotFoundHttpException("image command with ID {$command->getId()} not found");
        }

        $imageCommand->setCanceledAt(new DateTimeImmutable());
        $imageCommand->setCanceledBy($command->getUser()->getEmail());
        $this->entityManager->persist($imageCommand);
        $this->entityManager->flush();

        return new CancelCommandResponse($command->getId());
    }
}
