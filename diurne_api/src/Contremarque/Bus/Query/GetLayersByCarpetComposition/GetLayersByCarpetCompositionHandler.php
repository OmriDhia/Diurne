<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetLayersByCarpetComposition;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\LayerRepository;

class GetLayersByCarpetCompositionHandler implements QueryHandler
{
    public function __construct(
        private readonly LayerRepository $layerRepository
    ) {
    }

    public function __invoke(GetLayersByCarpetCompositionQuery $query): GetLayersByCarpetCompositionResponse
    {
        $carpetCompositionId = $query->getCarpetCompositionId();
        $layers = $this->layerRepository->findBy(['carpetComposition' => $carpetCompositionId]);

        return new GetLayersByCarpetCompositionResponse($layers);
    }
}
