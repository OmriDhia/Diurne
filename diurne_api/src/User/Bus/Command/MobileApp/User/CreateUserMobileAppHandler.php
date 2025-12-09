<?php

declare(strict_types=1);

namespace App\User\Bus\Command\MobileApp\User;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Bus\Command\CommandResponse;
use App\Common\Exception\ResourceNotFoundException;
use App\User\Entity\UserMobileApp;
use App\User\Repository\PermissionsMobileAppRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CreateUserMobileAppHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly PermissionsMobileAppRepository $permissionsRepository
    ) {}

    public function __invoke(CreateUserMobileAppCommand $command): CommandResponse
    {
        $permission = $this->permissionsRepository->find($command->permissionId);
        if (!$permission) {
            throw new ResourceNotFoundException('Permission not found');
        }

        $user = new UserMobileApp();
        $user->setName($command->name);
        $user->setEmail($command->email);
        $user->setPermission($permission);
        $user->setIsActive($command->isActive);
        $user->setPicture($command->picture);
        $user->setCreatedAt(new \DateTimeImmutable());

        // Hash password
        $hashedPassword = $this->passwordHasher->hashPassword($user, $command->password);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
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
