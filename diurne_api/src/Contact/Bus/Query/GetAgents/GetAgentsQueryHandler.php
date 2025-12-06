<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetAgents;

use InvalidArgumentException;
use App\Common\Bus\Query\QueryHandler;
use App\Contact\Repository\CustomerRepository;
use App\Contact\Repository\IntermediaryTypeRepository;

final readonly class GetAgentsQueryHandler implements QueryHandler
{
    public function __construct(
        private CustomerRepository $customerRepository,
        private IntermediaryTypeRepository $intermediaryTypeRepository
    ) {
    }

    public function __invoke(GetAgentsQuery $query)
    {
        $customerRepository = $this->customerRepository;

        // Apply pagination if parameters are set
        if (null !== $query->getPage() && null !== $query->getItemsPerPage()) {
            $customerRepository = $customerRepository->withPagination($query->getPage(), $query->getItemsPerPage());
        }

        $intermediaryType = $this->intermediaryTypeRepository->findOneByName('Agent');
        $qb = $customerRepository->query(); // Get a clone of the query builder
        $qb->join('customer.intermediaryInformationSheet', 'intermediaryInformationSheet');
        $qb->join('intermediaryInformationSheet.intermediaryType', 'type');
        $qb->where('type.id = :typeId');
        $qb->where('customer.isIntermediary = true');
        $qb->setParameter('typeId', $intermediaryType->getId());
        $qb->join('customer.contactInformationSheet', 'contactInformationSheet');

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

        foreach ($parameters as $key => $value) {
            $qb->setParameter($key, $value);
        }

        $paginator = $customerRepository->paginator($qb);

        return new GetAgentsResponse(
            (int) $paginator->getTotalItems(),
            (int) $paginator->getCurrentPage(),
            (int) $paginator->getItemsPerPage(),
            $paginator->get()
        );
    }
}
