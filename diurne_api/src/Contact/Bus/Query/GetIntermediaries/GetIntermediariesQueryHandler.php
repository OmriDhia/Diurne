<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetIntermediaries;

use InvalidArgumentException;
use App\Common\Bus\Query\QueryHandler;
use App\Contact\Repository\CustomerRepository;
use App\Contact\Repository\IntermediaryTypeRepository;

final readonly class GetIntermediariesQueryHandler implements QueryHandler
{
    public function __construct(
        private CustomerRepository $customerRepository,
        private IntermediaryTypeRepository $intermediaryTypeRepository
    ) {
    }

    public function __invoke(GetIntermediariesQuery $query)
    {
        $customerRepository = $this->customerRepository;

        // Apply pagination if parameters are set
        if (null !== $query->getPage() && null !== $query->getItemsPerPage()) {
            $customerRepository = $customerRepository->withPagination($query->getPage(), $query->getItemsPerPage());
        }
        $qb = $customerRepository->query(); // Get a clone of the query builder
        $qb->join('intermediary.intermediaryType', 'type');
        $qb->join('intermediary.contactInformationSheet', 'contactInformationSheet');

        // Determine the order direction and column, default to 'ASC' and 'id'
        $orderWay = $query->getOrderWay();
        $orderBy = $query->getOrderBy();

        // Ensure orderWay is either 'ASC' or 'DESC' to prevent SQL injection
        if (!empty($orderWay) && !in_array(strtoupper($orderWay), ['ASC', 'DESC'])) {
            throw new InvalidArgumentException('Invalid order direction');
        }

        // Ensure the orderBy field is a valid field in your intermediary entity
        $allowedOrderFields = ['firstname', 'lastname', 'email']; // Add other allowed fields here
        if (!empty($orderBy) && !in_array($orderBy, $allowedOrderFields)) {
            throw new InvalidArgumentException('Invalid order by field');
        }

        if (!empty($orderWay) && !empty($orderBy)) {
            // Apply ordering to the query
            $qb->orderBy('contactInformationSheet.'.$orderBy, $orderWay);
        }

        // Add dynamic filters
        $parameters = [];
        if (null !== $query->getFirstname()) {
            $qb->andWhere('contactInformationSheet.firstname = :firstname');
            $parameters['firstname'] = $query->getFirstname();
        }
        if (null !== $query->getLastname()) {
            $qb->andWhere('contactInformationSheet.lastname = :lastname');
            $parameters['lastname'] = $query->getLastname();
        }
        if (null !== $query->getEmail()) {
            $qb->andWhere('contactInformationSheet.email = :email');
            $parameters['email'] = $query->getEmail();
        }

        if (null !== $query->getIntermediaryTypeId()) {
            $qb->andWhere('type.id = :typeId');
            $qb->setParameter('typeId', $query->getIntermediaryTypeId());
        }

        foreach ($parameters as $key => $value) {
            $qb->setParameter($key, $value);
        }

        $paginator = $customerRepository->paginator($qb);

        return new GetIntermediariesResponse(
            (int) $paginator->getTotalItems(),
            (int) $paginator->getCurrentPage(),
            (int) $paginator->getItemsPerPage(),
            $paginator->get()
        );
    }
}
