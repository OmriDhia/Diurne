<?php

declare(strict_types=1);

namespace App\MobileAppApi\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\MobileAppApi\Entity\PermissionsMobileApp;
use App\MobileAppApi\Repository\PermissionsMobileAppRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMPermissionsMobileAppRepository extends DoctrineORMRepository implements PermissionsMobileAppRepository
{
    protected const ENTITY_CLASS = PermissionsMobileApp::class;
    protected const ALIAS = 'permissionsMobileApp';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function create(array $data): void
    {
        // TODO: Implement create() method.
    }

    public function update($entity, array $data): void
    {
        // TODO: Implement update() method.
    }

    public function save(PermissionsMobileApp $entity, bool $flush = false): void
    {
        $this->persist($entity);
        if ($flush) {
            $this->flush();
        }
    }
}
