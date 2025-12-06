<?php

namespace App\Contact\Bus\Query\GetContactsByCustomer;

use App\Common\Bus\Query\QueryHandler;
use App\Contact\Repository\ContactRepository;

final readonly class GetContactsByCustomerQueryHandler implements QueryHandler
{
    public function __construct(
        private ContactRepository $contactRepository
    ) {
    }

    public function __invoke(GetContactsByCustomerQuery $query): GetContactsByCustomerResponse
    {
        $contacts = $this->contactRepository->findBy(['customer' => $query->customerId]);

        $formattedContacts = array_map(fn($contact) => $contact->toArray(), $contacts);

        return new GetContactsByCustomerResponse($formattedContacts);
    }
}
