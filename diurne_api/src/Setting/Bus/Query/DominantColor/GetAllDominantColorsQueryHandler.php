<?php

namespace App\Setting\Bus\Query\DominantColor;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\DominantColorRepository;

class GetAllDominantColorsQueryHandler implements QueryHandler
{
    public function __construct(private readonly DominantColorRepository $dominantColorRepository)
    {
    }

    public function __invoke(GetAllDominantColorsQuery $query): GetAllDominantColorsResponse
    {
        $isFetchingAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = $isFetchingAll ? 0 : ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $isFetchingAll ? null : $query->getItemsPerPage();

        $dominatcolors = $isFetchingAll 
            ? $this->dominantColorRepository->findAll() 
            : $this->dominantColorRepository->findBy([], null, $limit, $offset);

        $totalItems = $this->dominantColorRepository->count([]);

        return new GetAllDominantColorsResponse(
            $dominatcolors,
            $totalItems,
            $query->getPage(),
            $query->getItemsPerPage()
        );
    }
}
