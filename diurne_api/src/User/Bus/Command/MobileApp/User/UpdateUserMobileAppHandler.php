<?php

declare(strict_types=1);

namespace App\User\Bus\Command\MobileApp\User;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Bus\Command\CommandResponse;
use App\Common\Exception\ResourceNotFoundException;
use App\User\Entity\UserMobileApp;
use App\User\Repository\PermissionsMobileAppRepository;
use App\User\Repository\UserMobileAppRepository;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateUserMobileAppHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserMobileAppRepository $userRepository,
        private readonly PermissionsMobileAppRepository $permissionsRepository
    ) {}

    public function __invoke(UpdateUserMobileAppCommand $command): CommandResponse
    {
        $user = $this->userRepository->find($command->id);
        if (!$user instanceof UserMobileApp) {
            throw new ResourceNotFoundException('User not found');
        }

        if ($command->name !== null) {
            $user->setName($command->name);
        }
        if ($command->email !== null) {
            $user->setEmail($command->email);
        }
        if ($command->isActive !== null) {
            $user->setIsActive($command->isActive);
        }
        if ($command->picture !== null) {
            $user->setPicture($command->picture);
        }
        if ($command->permissionId !== null) {
            $permission = $this->permissionsRepository->find($command->permissionId);
            if ($permission) {
                $user->setPermission($permission);
            }
        }

        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return new class($user->getId()) implements CommandResponse {
            public function __construct(public readonly int $id) {}
            public function toArray(): array
            {
                return ['id' => $this->id];
            }
        };
    }
}
