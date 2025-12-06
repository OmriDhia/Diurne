<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Profile;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\User\Entity\Profile;
use App\User\Repository\ProfileRepository;

class UpdateProfileCommandHandler implements CommandHandler
{
    public function __construct(private readonly ProfileRepository $profileRepository)
    {
    }

    public function __invoke(UpdateProfileCommand $command): ProfileResponse
    {
        $profile = $this->profileRepository->find((int) $command->getProfileId());

        if (!$profile instanceof Profile) {
            throw new ResourceNotFoundException();
        }
        $profile->setName($command->getName());
        $profile->setDiscount($command->getDiscount());
        $this->profileRepository->persist($profile);
        $this->profileRepository->flush();

        return new ProfileResponse($profile->getId(), $profile->getName());
    }
}
