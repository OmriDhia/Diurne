<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\CarpetCollection;

use App\Common\Bus\Query\CacheableQueryHandlerTrait;
use App\Common\Bus\Query\QueryHandler;
use App\Setting\Entity\CarpetCollection;
use App\Setting\Entity\CarpetCollectionLang;
use App\Setting\Entity\Model;
use App\Setting\Repository\CarpetCollectionRepository;

class GetAllCarpetCollectionsQueryHandler implements QueryHandler
{
    use CacheableQueryHandlerTrait;

    public function __construct(
        private readonly CarpetCollectionRepository $repository
    ) {}

    /**
     * @return CarpetCollectionQueryResponse
     */
    public function __invoke(GetAllCarpetCollectionsQuery $query): CarpetCollectionQueryResponse
    {
        $getAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $query->getItemsPerPage();

        if ($getAll) {
            $cacheKey = 'carpet_collections_all';

            if ($query->isForceRefresh()) {
                $this->clearCache($cacheKey);
            }

            $collectionsData = $this->getCachedResult(
                $cacheKey,
                fn() => $this->fetchAndMapCollections(),
                3600
            );

            $totalItems = count($collectionsData);
        } else {
            $collectionsData = $this->repository->findBy([], null, $limit, $offset);
            $totalItems = $this->repository->count([]);
        }

        return new CarpetCollectionQueryResponse(
            $collectionsData,
            $totalItems,
            $query->getPage(),
            $getAll ? $totalItems : $query->getItemsPerPage()
        );
    }

    /**
     * Fetches all CarpetCollection entities and maps them to an array.
     *
     * @return array<int, array<string, mixed>>
     */
    private function fetchAndMapCollections(): array
    {
        $collections = $this->repository->findAll();
        $collectionsData = [];

        foreach ($collections as $collection) {
            $collectionsData[] = $this->mapCarpetCollectionToArray($collection);
        }

        return $collectionsData;
    }

    /**
     * Maps a CarpetCollection entity to an array.
     *
     * @param CarpetCollection $collection
     * @return array<string, mixed>
     */
    private function mapCarpetCollectionToArray(CarpetCollection $collection): array
    {
        return [
            'id' => $collection->getId(),
            'reference' => $collection->getReference(),
            'code' => $collection->getCode(),
            'show_grid' => $collection->isShowGrid(),
            'special_shape' => $collection->getSpecialShape() ? $collection->getSpecialShape()->getId() : null,
            'police' => $collection->getPolice() ? $collection->getPolice()->getId() : null,
            'image_name' => $collection->getImageName(),
            'author' => $collection->getAuthor() ? $collection->getAuthor()->getId() : null,
            'created_at' => $collection->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $collection->getUpdatedAt()->format('Y-m-d H:i:s'),
            'carpet_collection_lang' => $this->mapCarpetCollectionLangs($collection->getCarpetCollectionLang()->toArray()),
            'models' => $this->mapModels($collection->getModels()->toArray()),
            'collection_group_id' => $collection->getCollectionGroup() ? $collection->getCollectionGroup()->getId() : null,
        ];
    }

    /**
     * Maps an array of CarpetCollectionLang entities to an array of data.
     *
     * @param CarpetCollectionLang[] $langs
     * @return array<int, array<string, mixed>>
     */
    private function mapCarpetCollectionLangs(array $langs): array
    {
        return array_map(
            fn(CarpetCollectionLang $lang) => [
                'id' => $lang->getId(),
                'description' => $lang->getDescription(),
                'language' => $lang->getLanguage() ? $lang->getLanguage()->getId() : null,
            ],
            $langs
        );
    }

    /**
     * Maps an array of Model entities to an array of data.
     *
     * @param Model[] $models
     * @return array<int, array<string, mixed>>
     */
    private function mapModels(array $models): array
    {
        return array_map(
            fn(Model $model) => [
                'id' => $model->getId(),
                'code' => $model->getCode(),
                'number_max' => $model->getNumberMax(),
            ],
            $models
        );
    }
}
