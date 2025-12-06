<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TarifTexture;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\TarifTextureRepository;

final readonly class GetByIdTarifTextureQueryHandler implements QueryHandler
{
    public function __construct(private TarifTextureRepository $tarifTextureRepository)
    {
    }

    public function __invoke(GetByIdTarifTextureQuery $query): \App\Setting\Bus\Query\TarifTexture\GetByIdTarifTextureResponse
    {
        $entity = $this->tarifTextureRepository->find($query->getId());

        if (null === $entity) {
            throw new ResourceNotFoundException();
        }

        return new \App\Setting\Bus\Query\TarifTexture\GetByIdTarifTextureResponse($entity);
    }
}
