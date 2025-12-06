<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetTypes;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\CarpetTypeRepository;

final readonly class GetCarpetTypesQueryHandler implements QueryHandler
{
    public function __construct(private CarpetTypeRepository $carpetTypeRepository)
    {
    }

    public function __invoke(GetCarpetTypesQuery $query): GetCarpetTypesResponse
    {
        $carpetTypes = $this->carpetTypeRepository->findAll();

        $formattedCarpetTypes = array_map(fn($carpetType) => [
            'id' => $carpetType->getId(),
            'name' => $carpetType->getName(),
        ], $carpetTypes);

        return new GetCarpetTypesResponse($formattedCarpetTypes);
    }
}
