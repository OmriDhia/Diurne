<?php

declare(strict_types=1);

namespace App\Menu\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Menu\Entity\Menu;
use App\Menu\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMMenuRepository extends DoctrineORMRepository implements MenuRepository
{
    protected const ENTITY_CLASS = Menu::class;
    protected const ALIAS = 'menu';

    /**
     * DoctrineORMMenuRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return |null
     */
    public function findBySlug($slug)
    {
        try {
            $object = $this->query()
                ->where('menu.slug = :slug')
                ->setParameter('slug', $slug)
                ->getQuery()->getSingleResult();
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException) {
            throw new DomainException('More than one result found');
        }

        return $object;
    }

    /**
     * @return void
     */
    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    /**
     * @return void
     */
    public function update($entity, array $data)
    {
        // TODO: Implement update() method.
    }
}
