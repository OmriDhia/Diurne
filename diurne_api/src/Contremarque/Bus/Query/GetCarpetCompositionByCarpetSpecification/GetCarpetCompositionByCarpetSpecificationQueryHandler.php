<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetCompositionByCarpetSpecification;

use Exception;
use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\CarpetCompositionRepository;

class GetCarpetCompositionByCarpetSpecificationQueryHandler implements QueryHandler
{
    public function __construct(private readonly CarpetCompositionRepository $carpetCompositionRepository)
    {
    }

    public function __invoke(GetCarpetCompositionByCarpetSpecificationQuery $query): GetCarpetCompositionByCarpetSpecificationResponse
    {
        $carpetSpecificationId = $query->getCarpetSpecificationId();
        $carpetComposition = $this->carpetCompositionRepository->findOneBy(['carpetSpecification' => $carpetSpecificationId]);

        if (!$carpetComposition) {
            throw new Exception('Carpet Composition not found for the specified Carpet Specification');
        }

        return new GetCarpetCompositionByCarpetSpecificationResponse($carpetComposition->toArray());
    }
}
