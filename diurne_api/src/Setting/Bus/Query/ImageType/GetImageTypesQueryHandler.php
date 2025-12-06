<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\ImageType;

use App\Common\Bus\Query\CacheableQueryHandlerTrait;
use App\Common\Bus\Query\QueryHandler;
use App\Setting\Entity\ImageType;
use App\Setting\Repository\ImageTypeRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetImageTypesQueryHandler implements QueryHandler
{
    use CacheableQueryHandlerTrait;

    public function __construct(
        private readonly ImageTypeRepository $imageTypeRepository
    ) {}

    public function __invoke(GetImageTypesQuery $query): ImageTypesQueryResponse
    {
        $getAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $query->getItemsPerPage();

        if ($getAll) {
            $cacheKey = 'image_types_all';

            if ($query->isForceRefresh()) {
                $this->clearCache($cacheKey);
            }

            $imageTypesData = $this->getCachedResult(
                $cacheKey,
                fn() => $this->fetchAndMapImageTypes(),
                3600
            );

            $totalItems = count($imageTypesData);
        } else {
            $imageTypesData = $this->imageTypeRepository->findBy([], null, $limit, $offset);
            $totalItems = $this->imageTypeRepository->count([]);
        }

        return new ImageTypesQueryResponse(
            $imageTypesData,
            $totalItems,
            $query->getPage(),
            $getAll ? $totalItems : $query->getItemsPerPage()
        );
    }

    /**
     * Fetches all ImageType entities and maps them to an array.
     *
     * @return array<int, array<string, mixed>>
     */
    private function fetchAndMapImageTypes(): array
    {
        $imageTypes = $this->imageTypeRepository->findAll();
        return array_map(
            fn(ImageType $imageType) => [
                'id' => $imageType->getId(),
                'name' => $imageType->getName(),
                'description' => $imageType->getDescription(),
                'category' => $imageType->getCategory(),
            ],
            $imageTypes
        );
    }
}
