<?php

declare(strict_types=1);

namespace App\MobileAppApi\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\MobileAppApi\Entity\UserMobileApp;
use App\MobileAppApi\Repository\UserMobileAppRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMUserMobileAppRepository extends DoctrineORMRepository implements UserMobileAppRepository
{
    protected const ENTITY_CLASS = UserMobileApp::class;
    protected const ALIAS = 'userMobileApp';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function search(?string $query): array
    {
        $qb = $this->query();

        if ($query) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like(self::ALIAS . '.name', ':query'),
                    $qb->expr()->like(self::ALIAS . '.email', ':query')
                )
            )
            ->setParameter('query', '%' . $query . '%');
        }

        return $qb->getQuery()->getResult();
    }

    public function create(array $data): void
    {
        // TODO: Implement create() method.
    }

    public function update($entity, array $data): void
    {
        // TODO: Implement update() method.
    }

    public function save(UserMobileApp $entity, bool $flush = false): void
    {
        $this->persist($entity);
        if ($flush) {
            $this->flush();
        }
    }
}
