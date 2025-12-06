<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetCustomerById;

use DateTimeImmutable;
use RuntimeException;
use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\Customer;
use App\Contact\Entity\CustomerIntermediaryHistory;
use App\Contact\Repository\CustomerRepository;
use App\Contact\Repository\IntermediaryTypeRepository;
use Doctrine\Common\Collections\Collection;

/**
 * Handles the query to retrieve customer information by ID.
 */
final readonly class GetCustomerByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private CustomerRepository $customerRepository,
        private IntermediaryTypeRepository $intermediaryTypeRepository
    ) {}

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetCustomerByIdQuery $query): GetCustomerByIdResponse
    {
        $customer = $this->customerRepository->findOneById((int) $query->customerId());
        if (null === $customer) {
            throw new ResourceNotFoundException('Customer not found with ID: ' . $query->customerId());
        }

        $customerData = $customer->toArray();
        $customerData = $this->enrichCustomerData($customer, $customerData);

        return new GetCustomerByIdResponse($customerData);
    }

    private function enrichCustomerData(Customer $customer, array $customerData): array
    {
        // Add firstName and lastName
        [$firstName, $lastName] = $this->getCustomerNameParts($customer);
        $customerData['firstname'] = $firstName;
        $customerData['lastname'] = $lastName;

        // Add is_agent flag
        $customerData['is_agent'] = $this->isAgent($customer);

        // Transform addresses
        $customerData['addressesData'] = $this->transformCollection($customerData['addresses'] ?? []);
        unset($customerData['addresses']);

        // Check for postal address
        $customerData['already_has_postal_address'] = $this->customerRepository->alreadyHasPostalAddress($customer->getId());

        // Transform contacts
        $customerData['contactsData'] = $this->transformCollection($customerData['contacts'] ?? []);
        unset($customerData['contacts']);

        // Transform and sort contact commercial histories
        $customerData['contactCommercialHistoriesData'] = $this->transformAndSortCommercialHistories($customerData['contactCommercialHistories'] ?? []);
        unset($customerData['contactCommercialHistories']);

        // Transform customer intermediary histories
        $customerData['customerIntermediaryHistoriesData'] = $this->transformIntermediaryHistories($customerData['customerIntermediaryHistories'] ?? []);
        unset($customerData['customerIntermediaryHistories']);

        return $customerData;
    }

    private function getCustomerNameParts(Customer $customer): array
    {
        $customerGroupName = $customer->getCustomerGroup()?->getName();
        if ($customerGroupName === 'Particulier (Client)') {
            $contactInfo = $customer->getContactInformationSheet();
            return [
                $contactInfo?->getFirstname() ?? '',
                $contactInfo?->getLastname() ?? '',
            ];
        }

        return [
            $customer->getSocialReason() ?? '',
            $customer->getCode() ?? '',
        ];
    }

    private function isAgent(Customer $customer): bool
    {
        if (!$customer->isIntermediary()) {
            return false;
        }

        $intermediaryType = $customer->getIntermediaryInformationSheet()?->getIntermediaryType();
        return $intermediaryType?->getName() === 'Agent';
    }

    /**
     * @param Collection<int, object>|array<object> $collection
     */
    private function transformCollection(Collection|array $collection): array
    {
        // Convert Collection to array if necessary
        $items = $collection instanceof Collection ? $collection->toArray() : $collection;

        return array_map(
            fn($item) => method_exists($item, 'toArray') ? $item->toArray() : (array) $item,
            $items
        );
    }

    private function transformAndSortCommercialHistories(Collection|array $histories): array
    {
        $transformed = $this->transformCollection($histories);

        usort($transformed, function (array $a, array $b): int {
            $dateA = $a['from'] ? new DateTimeImmutable($a['from']) : null;
            $dateB = $b['from'] ? new DateTimeImmutable($b['from']) : null;

            if ($dateA === null && $dateB === null) {
                return 0;
            }
            if ($dateA === null) {
                return 1;
            }
            if ($dateB === null) {
                return -1;
            }

            return $dateB <=> $dateA;
        });

        return $transformed;
    }

    private function transformIntermediaryHistories(Collection|array $histories): array
    {
        $agentType = $this->intermediaryTypeRepository->findOneByName('Agent');
        $prescripteurType = $this->intermediaryTypeRepository->findOneByName('Prescripteur');

        if ($agentType === null || $prescripteurType === null) {
            throw new RuntimeException('Intermediary types "Agent" or "Prescripteur" not found.');
        }

        $result = [
            'agents' => [],
            'prescripteurs' => [],
        ];

        $items = $histories instanceof Collection ? $histories->toArray() : $histories;
        foreach ($items as $history) {
            if (!$history instanceof CustomerIntermediaryHistory) {
                continue;
            }

            $intermediaryType = $history->getIntermediary()?->getIntermediaryInformationSheet()?->getIntermediaryType();
            if ($intermediaryType === null) {
                continue;
            }

            $intermediaryData = $this->getIntermediaryData($history);
            if ($intermediaryType->getId() === $agentType->getId()) {
                $result['agents'][] = $intermediaryData;
            } elseif ($intermediaryType->getId() === $prescripteurType->getId()) {
                $result['prescripteurs'][] = $intermediaryData;
            }
        }

        // Sort agents and prescripteurs by id in descending order
        usort($result['agents'], fn(array $a, array $b): int => $b['id'] <=> $a['id']);
        usort($result['prescripteurs'], fn(array $a, array $b): int => $b['id'] <=> $a['id']);

        return $result;
    }

    private function getIntermediaryData(CustomerIntermediaryHistory $history): array
    {
        $intermediary = $history->getIntermediary();
        if ($intermediary === null) {
            return [];
        }

        [$firstName, $lastName] = $this->getCustomerNameParts($intermediary);

        return [
            'id' => $history->getId(), // Add the history ID
            'customer_id' => $history->getCustomer()?->getId(),
            'firstname' => $firstName,
            'lastname' => $lastName,
            'intermediary_id' => $intermediary->getId(),
            'intermediaryType_id' => $intermediary->getIntermediaryInformationSheet()?->getIntermediaryType()?->getId(),
            'from' => $history->getDateFrom()?->format('Y-m-d H:i:s'),
            'to' => $history->getDateTo() ? $history->getDateTo()->format('Y-m-d H:i:s') : null,
        ];
    }
}
