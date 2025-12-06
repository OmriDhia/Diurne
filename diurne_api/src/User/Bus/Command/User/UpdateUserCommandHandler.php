<?php

declare(strict_types=1);

namespace App\User\Bus\Command\User;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\User\Entity\User;
use App\User\Repository\UserRepository;

final readonly class UpdateUserCommandHandler implements CommandHandler
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function __invoke(UpdateUserCommand $command): UpdateUserResponse
    {
        $user = $this->userRepository->find((int) $command->userId);
        if (!$user instanceof User) {
            throw new ResourceNotFoundException();
        }
        $user->setFirstname($command->getFirstName());
        $user->setLastname($command->getLastName());
        $user->setEmail($command->getEmail());
        $user->setPassword($command->getPassword());
        $user->setIsActive($command->isActive());
        $this->userRepository->persist($user);
        $this->userRepository->flush();

        return new UpdateUserResponse(
            (string) $user->getId(),
            (string) $user->getEmail(),
            (string) $user->getFirstName(),
            (string) $user->getLastName(),
            $user->isActive(),
        );
    }
}
