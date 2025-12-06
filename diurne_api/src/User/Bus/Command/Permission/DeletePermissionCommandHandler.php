<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Permission;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\User\Entity\Permission;
use App\User\Repository\PermissionRepository;

class DeletePermissionCommandHandler implements CommandHandler
{
    public function __construct(private readonly PermissionRepository $permissionRepository)
    {
    }

    public function __invoke(DeletePermissionCommand $command): PermissionResponse
    {
        $permission = $this->permissionRepository->findByName($command->getName());
        $id = $permission->getId();
        $name = $permission->getName();
        if (!$permission instanceof Permission) {
            throw new ResourceNotFoundException();
        }
        $this->permissionRepository->remove($permission);
        $this->permissionRepository->flush();

        return new PermissionResponse($id, $name);
    }
}
