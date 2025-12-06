<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetContremarquesByCustomerId;

use App\Common\Bus\Query\QueryHandler;
use App\Contact\Repository\CustomerRepository;
use App\Contremarque\Repository\ContremarqueRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class GetContremarquesByCustomerIdQueryHandler implements QueryHandler
{
    public function __construct(private EntityManagerInterface $entityManager, private ContremarqueRepository $contremarqueRepository, private CustomerRepository $customerRepository)
    {
    }

    public function __invoke(GetContremarquesByCustomerIdQuery $query): GetContremarquesByCustomerIdResponse
    {
        $repository = $this->contremarqueRepository;
        $customer = $this->customerRepository->find((int) $query->getCustomerId());
        // Fetch contremarques by customer ID
        $contremarques = $repository->findBy(['customer' => $customer]);

        return new GetContremarquesByCustomerIdResponse($contremarques);
    }
}
