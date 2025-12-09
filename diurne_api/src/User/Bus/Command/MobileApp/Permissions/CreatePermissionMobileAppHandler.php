<?php

declare(strict_types=1);

namespace App\User\Bus\Command\MobileApp\Permissions;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Bus\Command\CommandResponse;
use App\User\Entity\PermissionsMobileApp;
use App\User\Repository\PermissionsMobileAppRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CreatePermissionMobileAppHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function __invoke(CreatePermissionMobileAppCommand $command): CommandResponse
    {
        $permission = new PermissionsMobileApp();
        $permission->setName($command->name);
        $permission->setDescription($command->description);
        $permission->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($permission);
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
