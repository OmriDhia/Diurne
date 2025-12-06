<?php

namespace App\User\DataFixtures;

use App\User\Entity\Permission;
use App\User\Entity\Profile;
use App\User\Entity\User;
use App\User\Repository\PermissionRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    private UserPasswordHasherInterface $hasher;
    private TranslatorInterface $translator;
    private PermissionRepository $permissionRepository;

    public function load(ObjectManager $manager): void
    {
        $superAdminProfile = $this->createProfile('Super admin', 0, $manager);
        $designerProfile = $this->createProfile('Designer', 0, $manager);
        $designerManagerProfile = $this->createProfile('Designer manager', 0, $manager);

        $users = [
            ['lastname' => 'super admin', 'firstname' => 'super admin', 'email' => 'super-user@yopmail.com', 'profile' => $superAdminProfile],
            ['lastname' => 'Zelmanovitch', 'firstname' => 'Thomas', 'email' => 'thomas.zelmaoonovitch@diurne.com', 'profile' => $superAdminProfile],
            ['lastname' => 'Sayari', 'firstname' => 'Slim', 'email' => 'sayari.slim@gmail.com', 'profile' => $superAdminProfile],
            ['lastname' => 'Ajengui', 'firstname' => 'Anis', 'email' => 'ajenguianis@gmail.com', 'profile' => $superAdminProfile],
            ['lastname' => 'Abbassi', 'firstname' => 'Youssef', 'email' => 'youssefabbassi10@gmail.com', 'profile' => $superAdminProfile],
            ['lastname' => 'designer', 'firstname' => 'designer', 'email' => 'designer@yopmail.com', 'profile' => $designerProfile],
            ['lastname' => 'designer manager', 'firstname' => 'designer manager', 'email' => 'designer-manager@yopmail.com', 'profile' => $designerManagerProfile],
        ];

        foreach ($users as $userData) {
            $this->createUser($userData, $manager);
        }

        $this->createProfile('Commercial', 0, $manager);
        $this->createProfile('Commercial manager', 0, $manager);
        $this->createProfile('Client', 0, $manager);
        $this->createProfile('ADV', 0, $manager);
        $this->createProfile('Assistant commercial', 0, $manager);

        $permissions = $this->generatePermissions($manager, $superAdminProfile);
        $this->assignPermissions($permissions, $manager, $superAdminProfile, $designerProfile, $designerManagerProfile);

        $manager->flush();
    }

    private function createUser(array $userData, ObjectManager $manager): void
    {
        $user = new User();
        $user->setLastname($userData['lastname'])
            ->setFirstname($userData['firstname'])
            ->setEmail($userData['email'])
            ->setProfile($userData['profile']);

        $password = $this->hasher->hashPassword($user, '123@123@1234');
        $user->setPassword($password);

        $manager->persist($user);
    }

    private function createProfile(string $name, int $discount, ObjectManager $manager): Profile
    {
        $profile = new Profile();
        $profile->setName($name)
            ->setDiscount($discount);
        $manager->persist($profile);
        $manager->flush();

        return $profile;
    }

    private function generatePermissions(ObjectManager $manager, Profile $superAdminProfile): array
    {
        $entities = [
            'users' => 'user',
            'profiles' => 'profile',
            'commerciales' => 'commercial',
            'permissions' => 'permission',
            'contacts' => 'contact',
            'events' => 'event',
            'contremarques' => 'contremarque',
            'devis' => 'quote',
            'di' => 'DI',
            'orders' => 'order',
            'invoices' => 'invoice',
            'images' => 'image',
            'carpets' => 'carpet',
            'settings' => 'setting',
            'treasuries' => 'treasury',
            'deisgners' => 'designer',
        ];

        $permissions = [];
        foreach ($entities as $key => $entity) {
            $permissions[$key] = $this->createCrudPermissions($entity, $manager, $superAdminProfile);
        }

        return $permissions;
    }

    private function createCrudPermissions(string $entity, ObjectManager $manager, Profile $profile): array
    {
        $permissions = [];

        if ('commercial' === $entity) {
            $permissions['validate'] = $this->createPermission('validate', $entity, $manager, $profile);
        } elseif ('designer' === $entity) {
            $permissions['assign'] = $this->createPermission('assign', $entity, $manager, $profile);
        } else {
            $permissions['read'] = $this->createPermission('read', $entity, $manager, $profile);
            $permissions['create'] = $this->createPermission('create', $entity, $manager, $profile);
            $permissions['update'] = $this->createPermission('update', $entity, $manager, $profile);
            $permissions['delete'] = $this->createPermission('delete', $entity, $manager, $profile);
        }

        return $permissions;
    }

    private function createPermission(string $action, string $entity, ObjectManager $manager, Profile $profile): Permission
    {
        $permission = $this->permissionRepository->findByName("$action $entity");
        if (!$permission) {
            $permission = new Permission();
            $permission->setName("$action $entity")
                ->setEntity(ucfirst($entity))
                ->setPublicName($this->translator->trans("$action $entity"))
                ->setGuardName('api');
            $manager->persist($permission);
        }

        $profile->addPermission($permission);

        return $permission;
    }

    private function assignPermissions(array $permissions, ObjectManager $manager, Profile $superAdminProfile, Profile $designerProfile, Profile $designerManagerProfile): void
    {
        foreach ($permissions as $group => $groupPermissions) {
            foreach ($groupPermissions as $permission) {
                $superAdminProfile->addPermission($permission);

                if (in_array($permission->getEntity(), ['DI', 'Image', 'Contremarque'])) {
                    $designerProfile->addPermission($permission);
                    $designerManagerProfile->addPermission($permission);
                    $manager->persist($designerProfile);
                    $manager->persist($designerManagerProfile);
                }

                $manager->persist($superAdminProfile);
            }
        }
    }

    public function setHasher(UserPasswordHasherInterface $hasher): void
    {
        $this->hasher = $hasher;
    }

    public function setTranslator(TranslatorInterface $translator): static
    {
        $this->translator = $translator;

        return $this;
    }

    public function setPermissionRepository(PermissionRepository $permissionRepository): static
    {
        $this->permissionRepository = $permissionRepository;

        return $this;
    }

    public static function getGroups(): array
    {
        return ['user'];
    }
}
