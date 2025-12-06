<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Profile;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\User\Entity\Profile;
use App\User\Repository\ProfileRepository;

class CreateProfileCommandHandler implements CommandHandler
{
    public function __construct(private readonly ProfileRepository $profileRepository)
    {
    }

    public function __invoke(CreateProfileCommand $command): ProfileResponse
    {
        $profile = $this->profileRepository->findOneByName($command->getName());

        if ($profile instanceof Profile) {
            throw new DuplicateValidationResourceException();
        }
        $profile = $this->profileRepository->create(
            [
                'name' => $command->getName(),
                'discount' => $command->getDiscount(),
            ]
        );

        return new ProfileResponse($profile->getId(), $profile->getName());
    }
}
