<?php

namespace App\User\Command;

use App\User\Entity\Permission;
use App\User\Entity\Profile;
use App\User\Entity\User;
use App\User\Repository\PermissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(
    name: 'app:create-profiles-and-permissions',
    description: 'Create profiles and permissions for the application.'
)]
class CreateProfilesAndPermissionsCommand extends Command
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly TranslatorInterface         $translator,
        private readonly PermissionRepository        $permissionRepository,
        private readonly EntityManagerInterface      $entityManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('This command initializes profiles and their associated permissions.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Creating profiles and permissions...</info>');

        // Create Users
        $su = $this->createUserIfNotExists('super-user@yopmail.com', 'super admin', 'super admin', '123@123@1234', 'Super admin', 0);
        $designer = $this->createUserIfNotExists('designer@yopmail.com', 'designer', 'designer', '123@123@1234', 'Designer', 0);

        $this->entityManager->flush();

        // Function to create permissions
        $crud = function (string $key) use ($su): array {
            $permissions = [];

            if ('commercial' === $key) {
                $validate = $this->findOrCreatePermission('validate ' . $key, ucfirst($key), 'validate ' . $key);
                $su->getProfile()->addPermission($validate);
                $permissions['validate'] = $validate;
            } else {
                foreach (['read', 'create', 'update', 'delete'] as $action) {
                    $permission = $this->findOrCreatePermission("$action $key", ucfirst($key), "$action $key");
                    $su->getProfile()->addPermission($permission);
                    $permissions[$action] = $permission;
                }
            }

            return $permissions;
        };

        // Define entities and assign permissions
        $permissions = [];
        foreach (['user', 'profile', 'commercial', 'permission', 'contact', 'event', 'contremarque', 'quote', 'DI', 'order', 'invoice', 'image', 'carpet', 'treasury', 'setting', 'workshop', 'checkingList', 'progressReport'] as $entity) {
            $permissions[$entity] = $crud($entity);
        }

        // Assign DI & Image permissions to Designer
        foreach ($permissions as $groupPermissions) {
            foreach ($groupPermissions as $permission) {
                if (in_array($permission->getEntity(), ['DI', 'Image'], true)) {
                    $designer->getProfile()->addPermission($permission);
                    $this->entityManager->persist($designer);
                }
            }
        }

        // Save changes
        $this->entityManager->persist($su);
        $this->entityManager->flush();

        $output->writeln('<info>Profiles and permissions created successfully.</info>');

        return Command::SUCCESS;
    }

    private function createUserIfNotExists(string $email, string $lastname, string $firstname, string $password, string $profileName, int $discount): User
    {
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$existingUser instanceof User) {
            $user = new User();
            $user->setLastname($lastname);
            $user->setFirstname($firstname);
            $user->setEmail($email);
            $user->setPassword($this->hasher->hashPassword($user, $password));

            $profile = $this->createProfileIfNotExists($profileName, $discount);
            $user->setProfile($profile);

            $this->entityManager->persist($user);
            return $user;
        }

        return $existingUser;
    }

    private function createProfileIfNotExists(string $name, int $discount): Profile
    {
        $existingProfile = $this->entityManager->getRepository(Profile::class)->findOneBy(['name' => $name]);

        if (!$existingProfile instanceof Profile) {
            $profile = new Profile();
            $profile->setName($name);
            $profile->setDiscount($discount);
            $this->entityManager->persist($profile);

            return $profile;
        }

        return $existingProfile;
    }

    private function findOrCreatePermission(string $name, string $entity, string $publicName): Permission
    {
        $permission = $this->permissionRepository->findByName($name);

        if (!$permission instanceof Permission) {
            $permission = new Permission();
            $permission->setName($name);
            $permission->setEntity($entity);
            $permission->setPublicName($this->translator->trans($publicName));
            $permission->setGuardName('api');
            $this->entityManager->persist($permission);
        }

        return $permission;
    }
}
