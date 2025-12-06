<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetUsers;

use App\Common\Bus\Query\QueryHandler;
use App\User\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;

final readonly class GetUsersQueryHandler implements QueryHandler
{
    public function __construct(private UserRepository $userRepository) {}

    public function __invoke(GetUsersQuery $query)
    {
        $userRepository = $this->userRepository;
        // Apply pagination if parameters are set
        if (null !== $query->getPage() && null !== $query->getItemsPerPage()) {
            $userRepository = $userRepository->withPagination($query->getPage(), $query->getItemsPerPage());
        }

        $qb = $userRepository->query(); // Get a clone of the query builder

         // Array to hold filtering criteria
         // Array to hold query parameters

        if (!empty($query->getFirstname())) {
            $qb->AndWhere('user.firstname LIKE :firstname');
            $qb->setParameter('firstname', '%' . $query->getFirstname() . '%');
        }
        if (!empty($query->getLastname())) {
            $qb->AndWhere('user.lastname LIKE :lastname');
            $qb->setParameter('lastname', '%' . $query->getLastname() . '%');
        }
        if (!empty($query->getEmail())) {
            $qb->AndWhere('user.email = :email');
            $qb->setParameter('email', $query->getEmail());
        }
        if (!empty($query->getProfileId())) {
            $qb->innerJoin('user.profile', 'p'); // Use the defined alias 'u' here
            $qb->andWhere('p.id = :profileId');
            $qb->setParameter('profileId', $query->getProfileId());
        }

        if (!empty($query->getProfiles())) {
            $qb->innerJoin('user.profile', 'p'); // Use the defined alias 'u' here
            $qb->andWhere('p.name IN (:names)');
            $qb->setParameter('names', explode(',', (string) $query->getProfiles()));
        }
        if (!empty($query->getGender())) {
            $qb->leftJoin('user.gender', 'g'); // Use the defined alias 'u' here
            $qb->AndWhere('g.name LIKE :gender');
            $qb->setParameter('gender', '%' . $query->getGender() . '%');
        }
        if (null !== $query->getIsActive()) {
            $qb->andWhere('user.isActive = :isActive');
            $qb->setParameter('isActive', $query->getIsActive());
        }
        $qb->orderBy('user.id', 'DESC');
        $userRepository = $userRepository->filter(static function (QueryBuilder $qb) {
            $qb->select('user');
        });

        $paginator = $userRepository->paginator($qb);

        return new GetUsersResponse(
            (int) $paginator->getTotalItems(),
            (int) $paginator->getCurrentPage(),
            (int) $paginator->getItemsPerPage(),
            $paginator->get()
        );
    }
}
