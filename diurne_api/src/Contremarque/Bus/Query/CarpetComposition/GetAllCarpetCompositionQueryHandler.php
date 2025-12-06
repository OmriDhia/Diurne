<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\CarpetComposition;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\CarpetCompositionRepository;

class GetAllCarpetCompositionQueryHandler implements QueryHandler
{
    public function __construct(private readonly CarpetCompositionRepository $carpetCompositionRepository)
    {
    }

    public function __invoke(GetAllCarpetCompositionQuery $query): CarpetCompositionQueryResponse
    {
        $all_carpetComposition = $this->carpetCompositionRepository->findBy(['carpetSpecification' => $query->carpetSpecificationId()]);

        return new CarpetCompositionQueryResponse($all_carpetComposition);
    }
}
