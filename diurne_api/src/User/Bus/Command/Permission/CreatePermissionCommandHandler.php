<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Permission;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\User\Entity\Permission;
use App\User\Repository\PermissionRepository;

class CreatePermissionCommandHandler implements CommandHandler
{
    public function __construct(private readonly PermissionRepository $permissionRepository)
    {
    }

    public function __invoke(CreatePermissionCommand $command): PermissionResponse
    {
        $permission = $this->permissionRepository->findByName($command->getName());

        if ($permission instanceof Permission) {
            throw new DuplicateValidationResourceException();
        }
        $permission = new Permission();
        $permission->setName($command->getName());
        $permission->setPublicName($command->getPublicName());
        $permission->setGuardName($command->getGardName());
        $permission->setEntity($command->getEntity());
        $this->permissionRepository->persist($permission);
        $this->permissionRepository->flush();

        return new PermissionResponse($permission->getId(), $permission->getName());
    }
}
