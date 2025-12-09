<?php

declare(strict_types=1);

namespace App\User\Bus\Command\MobileApp\Permissions;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Bus\Command\CommandResponse;
use App\Common\Exception\ResourceNotFoundException;
use App\User\Entity\PermissionsMobileApp;
use App\User\Repository\PermissionsMobileAppRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DeletePermissionMobileAppHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PermissionsMobileAppRepository $permissionsRepository
    ) {}

    public function __invoke(DeletePermissionMobileAppCommand $command): CommandResponse
    {
        $permission = $this->permissionsRepository->find($command->id);
        if (!$permission instanceof PermissionsMobileApp) {
            throw new ResourceNotFoundException('Permission not found');
        }

        $this->entityManager->remove($permission);
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
