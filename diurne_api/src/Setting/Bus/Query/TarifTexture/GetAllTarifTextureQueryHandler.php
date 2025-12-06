<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TarifTexture;

use App\Common\Bus\Query\CacheableQueryHandlerTrait;
use App\Common\Bus\Query\QueryHandler;
use App\Setting\Entity\TarifTexture;
use App\Setting\Repository\TarifTextureRepository;

class GetAllTarifTextureQueryHandler implements QueryHandler
{
    use CacheableQueryHandlerTrait;

    public function __construct(private readonly TarifTextureRepository $tarifTextureRepository)
    {
    }

    public function __invoke(GetAllTarifTextureQuery $query): TarifTextureQueryResponse
    {
        $getAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = $getAll ? 0 : ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $getAll ? null : $query->getItemsPerPage();

        if ($getAll) {
            $cacheKey = 'tarif_textures_all';

            if ($query->isForceRefresh()) {
                $this->clearCache($cacheKey);
            }

            $items = $this->getCachedResult(
                $cacheKey,
                fn() => $this->fetchAndMapTarifTextures(),
                3600
            );

            $totalItems = count($items);
        } else {
            $items = $this->tarifTextureRepository->findBy([], null, $limit, $offset);
            $totalItems = $this->tarifTextureRepository->count([]);
        }

        return new TarifTextureQueryResponse(
            $items,
            $totalItems,
            $query->getPage(),
            $getAll ? $totalItems : $query->getItemsPerPage()
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchAndMapTarifTextures(): array
    {
        $entities = $this->tarifTextureRepository->findAll();

        return array_map(fn(TarifTexture $t) => $t->toArray(), $entities);
    }
}

