<?php

declare(strict_types=1);

namespace App\User\Bus\Command\MobileApp\User;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Bus\Command\CommandResponse;
use App\Common\Exception\ResourceNotFoundException;
use App\User\Entity\UserMobileApp;
use App\User\Repository\UserMobileAppRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DeleteUserMobileAppHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserMobileAppRepository $userRepository
    ) {}

    public function __invoke(DeleteUserMobileAppCommand $command): CommandResponse
    {
        $user = $this->userRepository->find($command->id);
        if (!$user instanceof UserMobileApp) {
            throw new ResourceNotFoundException('User not found');
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new class($command->id) implements CommandResponse {
            public function __construct(public readonly int $id) {}
            public function toArray(): array
            {
                return ['id' => $this->id];
            }
        };
    }
}
