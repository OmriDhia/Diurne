<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\GetAllMaterials;

use App\Common\Bus\Query\CacheableQueryHandlerTrait;
use App\Common\Bus\Query\QueryHandler;
use App\Setting\Entity\Material;
use App\Setting\Entity\MaterialLang;
use App\Setting\Repository\MaterialRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetAllMaterialsQueryHandler implements QueryHandler
{
    use CacheableQueryHandlerTrait;

    public function __construct(
        private readonly MaterialRepository $materialRepository
    ) {}

    public function __invoke(GetAllMaterialsQuery $query): MaterialQueryResponse
    {
        $getAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $query->getItemsPerPage();

        if ($getAll) {
            $cacheKey = 'materials_all';

            if ($query->isForceRefresh()) {
                $this->clearCache($cacheKey);
            }

            $materialsData = $this->getCachedResult(
                $cacheKey,
                fn() => $this->fetchAndMapMaterials(),
                3600
            );

            $totalItems = count($materialsData);
        } else {
            $materialsData = $this->materialRepository->findBy([], null, $limit, $offset);
            $totalItems = $this->materialRepository->count([]);
        }

        return new MaterialQueryResponse(
            $materialsData,
            $totalItems,
            $query->getPage(),
            $getAll ? $totalItems : $query->getItemsPerPage()
        );
    }

    /**
     * Fetches all Material entities and maps them to an array.
     *
     * @return array<int, array<string, mixed>>
     */
    private function fetchAndMapMaterials(): array
    {
        $materials = $this->materialRepository->findAll();
        $materialsData = [];

        foreach ($materials as $material) {
            $materialsData[] = $this->mapMaterialToArray($material);
        }

        return $materialsData;
    }

    /**
     * Maps a Material entity to an array.
     *
     * @param Material $material
     * @return array<string, mixed>
     */
    private function mapMaterialToArray(Material $material): array
    {
        return [
            'id' => $material->getId(),
            'reference' => $material->getReference(),
            'descriptions' => $this->getDescriptions($material),
        ];
    }

    /**
     * Maps MaterialLang entities to an array of descriptions.
     *
     * @param Material $material
     * @return array<int, array<string, mixed>>
     */
    private function getDescriptions(Material $material): array
    {
        $descriptions = [];
        foreach ($material->getMaterialLangs() as $index => $materialLang) {
            $descriptions[$index]['id_lang'] = $materialLang->getLanguage()->getId();
            $descriptions[$index]['iso_code'] = $materialLang->getLanguage()->getIsoCode();
            $descriptions[$index]['label'] = $materialLang->getLabel();
        }

        return $descriptions;
    }
}
