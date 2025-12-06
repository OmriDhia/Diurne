<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\CarpetCollection;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\CarpetCollection;
use App\Setting\Entity\CarpetCollectionLang;
use App\Setting\Entity\Model;

class CarpetCollectionQueryResponse implements QueryResponse
{
    /**
     * @param array<int, array<string, mixed>|CarpetCollection> $collections
     */
    public function __construct(private readonly array $collections, private readonly int $totalItems, private readonly ?int $page, private readonly int $itemsPerPage)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $collectionsData = [];
        foreach ($this->collections as $collection) {
            if ($collection instanceof CarpetCollection) {
                $collectionsData[] = $this->mapCarpetCollectionToArray($collection);
            } else {
                $collectionsData[] = $collection; // Already an array from cache
            }
        }

        return [
            'data' => $collectionsData,
            'meta' => [
                'total_items' => $this->totalItems,
                'page' => $this->page ?? 1,
                'items_per_page' => $this->itemsPerPage,
            ],
        ];
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
