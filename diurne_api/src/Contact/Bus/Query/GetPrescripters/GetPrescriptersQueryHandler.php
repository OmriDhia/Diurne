<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetPrescripters;

use App\Common\Bus\Query\QueryHandler;
use App\Contact\Repository\CustomerRepository;
use App\Contact\Repository\IntermediaryTypeRepository;
use Doctrine\ORM\QueryBuilder;

final readonly class GetPrescriptersQueryHandler implements QueryHandler
{
    public function __construct(
        private CustomerRepository $customerRepository,
        private IntermediaryTypeRepository $intermediaryTypeRepository
    ) {
    }

    public function __invoke(GetPrescriptersQuery $query)
    {
        $customerRepository = $this->customerRepository;
        // Apply pagination if parameters are set
        if (null !== $query->getPage() && null !== $query->getItemsPerPage()) {
            $customerRepository = $customerRepository->withPagination($query->getPage(), $query->getItemsPerPage());
        }
        $intermediaryType = $this->intermediaryTypeRepository->findOneByName('Prescripteur');
        $qb = $customerRepository->query(); // Get a clone of the query builder
        $qb->join('intermediary.intermediaryType', 'type');
        $qb->where('type.id = :typeId');
        $qb->setParameter('typeId', $intermediaryType->getId());
        $customerRepository = $customerRepository->filter(static function (QueryBuilder $qb) {
            $qb->select('intermediary');
        });

        $paginator = $customerRepository->paginator($qb);

        return new GetPrescriptersResponse(
            (int) $paginator->getTotalItems(),
            (int) $paginator->getCurrentPage(),
            (int) $paginator->getItemsPerPage(),
            $paginator->get()
        );
    }
}
