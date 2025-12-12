<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\MobileApp\Permissions;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Bus\Command\CommandResponse;
use App\Common\Exception\ResourceNotFoundException;
use App\MobileAppApi\Entity\PermissionsMobileApp;
use App\MobileAppApi\Repository\PermissionsMobileAppRepository;
use Doctrine\ORM\EntityManagerInterface;

final class UpdatePermissionMobileAppHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PermissionsMobileAppRepository $permissionsRepository
    ) {}

    public function __invoke(UpdatePermissionMobileAppCommand $command): CommandResponse
    {
        $permission = $this->permissionsRepository->find($command->id);
        if (!$permission instanceof PermissionsMobileApp) {
            throw new ResourceNotFoundException('Permission not found');
        }

        if ($command->name !== null) {
            $permission->setName($command->name);
        }
        if ($command->description !== null) {
            $permission->setDescription($command->description);
        }

        $permission->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        return new class($permission->getId()) implements CommandResponse {
            public function __construct(public readonly int $id) {}
            public function toArray(): array
            {
                return ['id' => $this->id];
            }
        };
    }
}
