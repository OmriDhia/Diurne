<?php

namespace App\CheckingList\Bus\Command\UpdateCheckingList;

use App\CheckingList\Entity\CheckingList;
use App\CheckingList\Repository\CheckingListRepository;
use App\Workshop\Repository\WorkshopOrderRepository;
use App\User\Repository\UserRepository;
use App\Common\Bus\Command\CommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use DateTime;

class UpdateCheckingListHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CheckingListRepository $checkingListRepository,
        private readonly WorkshopOrderRepository $workshopOrderRepository,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(UpdateCheckingListCommand $command): UpdateCheckingListResponse
    {
        $checkingList = $this->checkingListRepository->find($command->id);
        if (!$checkingList) {
            throw new EntityNotFoundException('CheckingList not found');
        }

        if (null !== $command->authorId) {
            $author = $this->userRepository->findById($command->authorId);
            if (!$author) {
                throw new EntityNotFoundException('User not found');
            }
            $checkingList->setAuthor($author);
        }
        if (null !== $command->date) {
            $checkingList->setDate($command->date);
        }
        if (null !== $command->dateEndProd) {
            $checkingList->setDateEndProd($command->dateEndProd);
        }
        if (null !== $command->comment) {
            $checkingList->setComment($command->comment);
        }

        $this->entityManager->flush();

        return new UpdateCheckingListResponse($checkingList);
    }
}
