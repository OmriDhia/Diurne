<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetAddressTypes;

use App\Common\Bus\Query\QueryHandler;
use App\Contact\Repository\AddressTypeRepository;

final readonly class GetAddressTypesQueryHandler implements QueryHandler
{
    public function __construct(private AddressTypeRepository $addressTypeRepository)
    {
    }

    public function __invoke(GetAddressTypesQuery $query)
    {
        $addressTypes = $this->addressTypeRepository->findAll();

        $formattedAddressTypes = array_map(fn($addressType) => [
            'addressType_id' => $addressType->getId(),
            'name' => $addressType->getName(),
        ], $addressTypes);

        return new GetAddressTypesResponse(
            $formattedAddressTypes
        );
    }
}
