<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetDefaultComposition;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\MaterialLangRepository;
use App\Setting\Repository\QualityRepository;

final readonly class GetDefaultCompositionQueryHandler implements QueryHandler
{
    public function __construct(
        private MaterialLangRepository $materialRepository,
        private QualityRepository $qualityRepository
    ) {
    }

    public function __invoke(GetDefaultCompositionQuery $query): GetDefaultCompositionQueryResponse
    {
        $defaults = [
            [
                'Material' => 'Wool',
                'Percentage' => 0.1,
                'Quality' => 'Nagpur',
            ],
            [
                'Material' => 'Silk',
                'Percentage' => 0.3,
                'Quality' => 'Nagpur',
            ],
            [
                'Material' => 'Viscose',
                'Percentage' => 0.6,
                'Quality' => 'Nagpur',
            ],
        ];

        $defaultComposition = [];

        foreach ($defaults as $index => $item) {
            $material = $this->materialRepository->findMaterialByLabel($item['Material']);
            $defaultComposition[$index]['materialId'] = $material->getId();
            $defaultComposition[$index]['percentage'] = $item['Percentage'];
            $quality = $this->qualityRepository->findQualityByName($item['Quality']);
            $defaultComposition[$index]['qualityId'] = $quality->getId();
        }

        return new GetDefaultCompositionQueryResponse($defaultComposition);
    }
}
