<?php

declare(strict_types=1);

namespace App\User\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\User\Entity\Permission;
use App\User\Repository\PermissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMPermissionRepository extends DoctrineORMRepository implements PermissionRepository
{
    protected const ENTITY_CLASS = Permission::class;
    protected const ALIAS = 'permission';

    /**
     * DoctrineORMPermissionRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return |null
     */
    public function findByName($name)
    {
        try {
            $object = $this->query()
                ->where('permission.name = :name')
                ->setParameter('name', $name)
                ->getQuery()->getSingleResult();
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException) {
            throw new DomainException('More than one result found');
        }

        return $object;
    }

    /**
     * @return Permission
     */
    public function create(array $data)
    {
        $permission = new Permission();
        $permission->setName($data['name']);
        $permission->setPublicName($data['public_name']);
        $permission->setGuardName($data['guard_name']);
        $this->persist($permission);
        $this->flush();

        return $permission;
    }

    /**
     * @param object $permission
     *
     * @return object
     */
    public function update($permission, array $data)
    {
        $permission->setPublicName($data['public_name']);
        $permission->setGuardName($data['guard_name']);
        $this->persist($permission);
        $this->flush();

        return $permission;
    }
}
