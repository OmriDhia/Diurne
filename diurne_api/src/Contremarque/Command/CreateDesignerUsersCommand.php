<?php

namespace App\Contremarque\Command;

use Exception;
use App\User\Entity\Permission;
use App\User\Entity\Profile;
use App\User\Entity\User;
use App\User\Repository\PermissionRepository;
use App\User\Repository\ProfileRepository;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(
    name: 'app:create-designer-users',
    description: 'Creates a Designer and Designer Manager with specific permissions.'
)]
class CreateDesignerUsersCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly TranslatorInterface $translator,
        private readonly PermissionRepository $permissionRepository,
        private readonly ProfileRepository $profileRepository,
        private readonly UserRepository $userRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            // Create profiles if not exist
            $designerProfile = $this->createOrGetProfile('Designer');
            $designerManagerProfile = $this->createOrGetProfile('Designer manager');
            $designerPermissions = $this->createOrGetPermissions('contremarque');
            $this->addPermissionsToProfile($designerManagerProfile, $designerPermissions);

            // Create or get existing users
            $designerUser = $this->createOrGetUser('designer-di@yopmail.com', 'Designer', 'John', $designerProfile);
            $designerManagerUser = $this->createOrGetUser('designer-manager-di@yopmail.com', 'Designer Manager', 'Jane', $designerManagerProfile);

            // Ensure Designer permissions are created and assigned
            $designerPermissions = $this->createOrGetPermissions('designer');
            $this->addPermissionsToProfile($designerManagerProfile, $designerPermissions);

            $designerPermissions = $this->createOrGetPermissions('contremarque');
            $this->addPermissionsToProfile($designerManagerProfile, $designerPermissions);

            // Persist entities
            $this->entityManager->persist($designerProfile);
            $this->entityManager->persist($designerManagerProfile);
            $this->entityManager->persist($designerUser);
            $this->entityManager->persist($designerManagerUser);

            $this->entityManager->flush();

            $io->success('Designer and Designer Manager users have been created or updated with the specified permissions.');
        } catch (Exception $e) {
            $io->error('An error occurred: ' . $e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function createOrGetUser(string $email, string $lastname, string $firstname, Profile $profile): User
    {
        // Check if the user already exists
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            // If the user does not exist, create a new one
            $user = new User();
            $user->setEmail($email);
            $user->setLastname($lastname);
            $user->setRoles([
                'ROLE_USER',
            ]);
            $user->setFirstname($firstname);
            $user->setPassword($this->hasher->hashPassword($user, '123@123@1234'));
            $user->setProfile($profile);
        } else {
            // If the user exists, update their profile if necessary
            if ($user->getProfile() !== $profile) {
                $user->setProfile($profile);
            }
        }

        return $user;
    }

    private function createOrGetProfile(string $name): Profile
    {
        $profile = $this->profileRepository->findOneBy(['name' => $name]);

        if (!$profile) {
            $profile = new Profile();
            $profile->setName($name);
            $profile->setDiscount(0); // Set discount if needed
        }

        return $profile;
    }

    private function createOrGetPermissions(string $key): array
    {
        $permissions = [];
        $actions = ['assign'];

        foreach ($actions as $action) {
            $permissionName = "$action $key";
            $permission = $this->permissionRepository->findOneBy(['name' => $permissionName]);

            if (!$permission) {
                $permission = new Permission();
                $permission->setName($permissionName);
                $permission->setEntity(ucfirst($key));
                $permission->setPublicName($this->translator->trans($permissionName));
                $permission->setGuardName('api');
            }

            $permissions[$action] = $permission;
        }

        $actions = ['read'];
        foreach ($actions as $action) {
            $permissionName = "$action $key";
            $permission = $this->permissionRepository->findOneBy(['name' => $permissionName]);

            if (!$permission) {
                $permission = new Permission();
                $permission->setName($permissionName);
                $permission->setEntity(ucfirst($key));
                $permission->setPublicName($this->translator->trans($permissionName));
                $permission->setGuardName('api');
            }

            $permissions[$action] = $permission;
        }

        return $permissions;
    }

    private function addPermissionsToProfile(Profile $profile, array $permissions): void
    {
        foreach ($permissions as $permission) {
            if (!$profile->getPermission()->contains($permission)) {
                $profile->addPermission($permission);
                $this->entityManager->persist($permission);
            }
        }
    }
}
