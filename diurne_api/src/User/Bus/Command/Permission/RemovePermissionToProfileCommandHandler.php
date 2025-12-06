<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Permission;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\User\Entity\Permission;
use App\User\Entity\Profile;
use App\User\Repository\PermissionRepository;
use App\User\Repository\ProfileRepository;

class RemovePermissionToProfileCommandHandler implements CommandHandler
{
    public function __construct(private readonly PermissionRepository $permissionRepository, private readonly ProfileRepository $profileRepository)
    {
    }

    public function __invoke(RemovePermissionToProfileCommand $command): ProfilePermissions
    {
        $permission = $this->permissionRepository->find($command->getPermissionId());
        $profile = $this->profileRepository->find($command->getProfileId());

        if (!$permission instanceof Permission) {
            throw new ResourceNotFoundException();
        }
        if (!$profile instanceof Profile) {
            throw new ResourceNotFoundException();
        }
        $profile->removePermission($permission);
        $this->profileRepository->persist($profile);
        $this->profileRepository->flush();
        $permissions = $profile->getPermission()->toArray();

        return new ProfilePermissions($profile->getName(), $permissions);
    }
}
