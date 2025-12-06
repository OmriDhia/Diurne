<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Profile;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\User\Entity\Profile;
use App\User\Entity\User;
use App\User\Repository\ProfileRepository;
use App\User\Repository\UserRepository;

class AssignProfileToUserCommandHandler implements CommandHandler
{
    public function __construct(private readonly ProfileRepository $profileRepository, private readonly UserRepository $userRepository)
    {
    }

    public function __invoke(AssignProfileToUserCommand $command): AssignProfileToUserResponse
    {
        $profile = $this->profileRepository->find($command->getProfileId());

        if (!$profile instanceof Profile) {
            throw new ResourceNotFoundException();
        }
        $user = $this->userRepository->findByEmail($command->getUserEmail());

        if (!$user instanceof User) {
            throw new ResourceNotFoundException();
        }

        $user->setProfile($profile);
        $this->userRepository->save($user);
        $this->userRepository->flush();

        return new AssignProfileToUserResponse($profile->getName(), $user->getEmail());
    }
}
