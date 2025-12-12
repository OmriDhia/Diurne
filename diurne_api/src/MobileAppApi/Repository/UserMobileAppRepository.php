<?php

namespace App\MobileAppApi\Repository;

use App\MobileAppApi\Entity\UserMobileApp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserMobileApp>
 *
 * @method UserMobileApp|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMobileApp|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMobileApp[]    findAll()
 * @method UserMobileApp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMobileAppRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserMobileApp::class);
    }
    public function search(?string $query): array
    {
        $qb = $this->createQueryBuilder('u');

        if ($query) {
            $qb->where('u.name LIKE :query')
               ->orWhere('u.email LIKE :query')
               ->setParameter('query', '%' . $query . '%');
        }

        return $qb->getQuery()->getResult();
    }
}
