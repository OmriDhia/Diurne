<?php

namespace App\User\Command;

use App\User\Entity\PermissionsMobileApp;
use App\User\Entity\UserMobileApp;
use App\User\Repository\PermissionsMobileAppRepository;
use App\User\Repository\UserMobileAppRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'user:load-mobile-data',
    description: 'Loads dummy data for mobile app users and permissions',
)]
class LoadMobileAppDataCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PermissionsMobileAppRepository $permissionsRepository,
        private readonly UserMobileAppRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $roles = ['admin', 'interne', 'workshop', 'users'];
        $permissions = [];

        // 1. Create Permissions
        foreach ($roles as $roleName) {
            $permission = $this->permissionsRepository->findOneBy(['name' => $roleName]);
            if (!$permission) {
                $permission = new PermissionsMobileApp();
                $permission->setName($roleName);
                $permission->setDescription("Permission for $roleName");
                $this->entityManager->persist($permission);
                $output->writeln("Created permission: $roleName");
            } else {
                $output->writeln("Permission already exists: $roleName");
            }
            $permissions[$roleName] = $permission;
        }

        $this->entityManager->flush();

        // 2. Create Users
        foreach ($roles as $roleName) {
            $email = "$roleName@diurne.com";
            $user = $this->userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                $user = new UserMobileApp();
                $user->setName($roleName); // User name same as role for simplicity per prompt
                $user->setEmail($email);
                $user->setIsActive(true);
                $user->setPermission($permissions[$roleName]);
                $user->setCreatedAt(new \DateTimeImmutable());

                $hashedPassword = $this->passwordHasher->hashPassword($user, '12345678');
                $user->setPassword($hashedPassword);

                $this->entityManager->persist($user);
                $output->writeln("Created user: $roleName ($email)");
            } else {
                $output->writeln("User already exists: $roleName");
            }
        }

        $this->entityManager->flush();
        $output->writeln("Dummy data loaded successfully.");

        return Command::SUCCESS;
    }
}
