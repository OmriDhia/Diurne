<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetLayersByCarpetComposition;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\Layer;

class GetLayersByCarpetCompositionResponse implements QueryResponse
{
    public function __construct(
        /**
         * @var Layer[]
         */
        private readonly array $layers
    )
    {
    }

    public function toArray(): array
    {
        return array_map(static fn(Layer $layer) => [
            'id' => $layer->getId(),
            'layer_number' => $layer->getLayerNumber(),
            'remarque' => $layer->getRemarque(),
        ], $this->layers);
    }
}
