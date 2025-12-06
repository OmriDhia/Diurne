<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Color;

use App\Common\Bus\Query\CacheableQueryHandlerTrait;
use App\Common\Bus\Query\QueryHandler;
use App\Setting\Entity\Color;
use App\Setting\Repository\ColorRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetAllColorQueryHandler implements QueryHandler
{
    use CacheableQueryHandlerTrait;

    public function __construct(
        private readonly ColorRepository $colorRepository
    ) {}

    public function __invoke(GetAllColorQuery $query): ColorQueryResponse
    {
        $getAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $query->getItemsPerPage();

        if ($getAll) {
            $cacheKey = 'colors_all';

            if ($query->isForceRefresh()) {
                $this->clearCache($cacheKey);
            }

            $colorsData = $this->getCachedResult(
                $cacheKey,
                fn() => $this->fetchAndMapColors(),
                3600
            );

            $totalItems = count($colorsData);
        } else {
            $colorsData = $this->colorRepository->findBy([], null, $limit, $offset);
            $totalItems = $this->colorRepository->count([]);
        }

        return new ColorQueryResponse(
            $colorsData,
            $totalItems,
            $query->getPage(),
            $getAll ? $totalItems : $query->getItemsPerPage()
        );
    }

    /**
     * Fetches all Color entities and maps them to an array.
     *
     * @return array<int, array<string, mixed>>
     */
    private function fetchAndMapColors(): array
    {
        $colors = $this->colorRepository->findAll();
        return array_map(
            fn(Color $color) => [
                'id' => $color->getId(),
                'reference' => $color->getReference(),
                'hexCode' => $color->getHexCode(),
            ],
            $colors
        );
    }
}
