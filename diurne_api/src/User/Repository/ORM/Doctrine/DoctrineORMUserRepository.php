<?php

declare(strict_types=1);

namespace App\User\Repository\ORM\Doctrine;

use DomainException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMUserRepository extends DoctrineORMRepository implements UserRepository
{
    protected const ENTITY_CLASS = User::class;
    protected const ALIAS = 'user';

    /**
     * DoctrineORMUserRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * Find a user by username.
     *
     * This method will return a User entity that matches the given username.
     * If no user is found, it will return null.
     */
    public function findByEmail($email): ?User
    {
        try {
            $object = $this->query()
                ->where('user.email = :email')
                ->setParameter('email', $email)
                ->getQuery()->getSingleResult();
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException) {
            throw new DomainException('More than one result found');
        }

        return $object;
    }

    /**
     * Find a user by id.
     *
     * This method will return a User entity that matches the given id.
     * If no user is found, it will return null.
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function findById($id): ?User
    {
        $object = $this->manager->find(self::ENTITY_CLASS, $id);
        if ($object instanceof User) {
            return $object;
        }

        return null;
    }

    /**
     * Find all users.
     *
     * This method will return an array of all User entities.
     * If no users are found, it will return an empty array.
     *
     * @return User[]
     */
    public function findAll(): array
    {
        return $this->findAll();
    }

    /**
     * Save a user.
     *
     * This method will save the given User entity.
     *
     * @param User $user the User entity to be saved
     */
    public function save(User $user): void
    {
        $this->manager->persist($user);
    }

    /**
     * Delete a user.
     *
     * This method will delete the given User entity from the database.
     *
     * @param User $user the User entity to be deleted
     */
    public function delete(User $user): void
    {
        $this->manager->remove($user);
    }

    public function persist(object $entity): void
    {
        // TODO: Implement persist() method.
    }

    /**
     * @return void
     */
    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    /**
     * @param object $entity
     *
     * @return void
     */
    public function update($entity, array $data)
    {
        // TODO: Implement update() method.
    }

    /**
     * @return bool
     */
    public function userCanDo(User $user, $permission)
    {
        $qb = $this->manager->createQueryBuilder();

        $qb->select('COUNT(p.id)')
            ->from(User::class, 'u')
            ->join('u.profile', 'pr')
            ->join('pr.permission', 'p');

        // Check by permission ID (priority over name)
        if (is_numeric($permission)) {
            $qb->where('p.id = :permissionId')
                ->setParameter('permissionId', $permission);
        } else {
            // Check by permission name (fallback)
            $qb->where('p.name = :permissionName')
                ->setParameter('permissionName', $permission);
        }

        $result = $qb->getQuery()->getSingleScalarResult();

        return (bool) $result;
    }

    /**
     * @psalm-return list<mixed>
     */
    public function getAccessibleMenus($userId): array
    {
        $sql = 'SELECT DISTINCT m.*, m.parent_id AS parent_id 
                FROM `menu` m
                INNER JOIN menu_permission mp ON m.id = mp.menu_id
                INNER JOIN profile_permission pp ON pp.permission_id = mp.permission_id
                INNER JOIN profile p ON p.id = pp.profile_id
                INNER JOIN user u ON u.profile_id = p.id
                WHERE u.id = :userId
                ORDER BY m.parent_id, m.position';

        $stmt = $this->manager->getConnection()->prepare($sql);
        $menus = $stmt->executeQuery(['userId' => $userId])->fetchAllAssociative();
        $menuTree = $this->buildNestedMenu($menus);

        return $menuTree;
    }

    /**
     * @psalm-return list<mixed>
     */
    private function buildNestedMenu($menus): array
    {
        $topLevelMenus = [];
        $childMenus = [];

        foreach ($menus as $menu) {
            $parentId = $menu['parent_id'];
            if (0 === $parentId) {
                $topLevelMenus[] = $menu;
            } else {
                $childMenus[$parentId][] = $menu;
            }
        }

        foreach ($topLevelMenus as &$topMenu) {
            $topMenu['children'] = $childMenus[$topMenu['id']] ?? [];
        }

        return $topLevelMenus;
    }

    public function getDummyUser()
    {
        $qb = $this->manager->createQueryBuilder();
        $qb->select('u')
            ->from(User::class, 'u');
        $qb->where('u.email LIKE :email')
            ->setParameter('email', '%dummy%');

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function getCommercialUser()
    {
        $qb = $this->manager->createQueryBuilder();
        $qb->select('u')
            ->from(User::class, 'u');
        $qb->where('u.email LIKE :email')
            ->setParameter('email', 'commercial-%');

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @return false|null|object
     */
    public function selectRandomCommercial(): object|false|null
    {
        $sql = "SELECT u.id FROM `user` u 
          WHERE u.email LIKE '%commercial%'
          ORDER BY RAND() ASC LIMIT 1";
        $stmt = $this->manager->getConnection()->prepare($sql);
        $id = $stmt->execute()->fetchOne();
        if (empty($id)) {
            return false;
        }

        return $this->find((int) $id);
    }

    public function findAvailableDesigner(CarpetDesignOrder $carpetDesignOrder): ?User
    {
        $carpetDesignOrderId = $carpetDesignOrder->getId();

        $sql = "
        SELECT u.id 
        FROM user u
        LEFT JOIN profile p ON u.profile_id = p.id
        WHERE p.name = 'Designer' 
        AND NOT EXISTS (
            SELECT 1 
            FROM designer_assignment da 
            WHERE da.designer_id = u.id 
            AND da.carpet_design_order_id = :carpetDesignOrderId
        )
    ";

        $stmt = $this->manager->getConnection()->prepare($sql);
        $stmt->executeQuery(['carpetDesignOrderId' => $carpetDesignOrderId]);

        $result = $stmt->executeQuery(['carpetDesignOrderId' => $carpetDesignOrderId])->fetchAssociative();

        if (false === $result) {
            return null; // No result found
        }

        $userId = $result['id'];

        // Return the User entity by its ID
        return $this->find($userId);
    }
}
