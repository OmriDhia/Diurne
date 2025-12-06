<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetIntermediaryTypes;

use App\Common\Bus\Query\QueryHandler;
use App\Contact\Repository\IntermediaryTypeRepository;

final readonly class GetIntermediaryTypesQueryHandler implements QueryHandler
{
    public function __construct(private IntermediaryTypeRepository $intermediaryTypeRepository)
    {
    }

    public function __invoke(GetIntermediaryTypesQuery $query)
    {
        $intermediaryTypes = $this->intermediaryTypeRepository->findAll();

        $formattedIntermediaryTypes = array_map(fn($intermediaryType) => [
            'intermediaryType_id' => $intermediaryType->getId(),
            'name' => $intermediaryType->getName(),
        ], $intermediaryTypes);

        return new GetIntermediaryTypesResponse(
            $formattedIntermediaryTypes
        );
    }
}
