<?php

declare(strict_types=1);

namespace App\Event\Bus\Query\GetNomenclatures;

use App\Common\Bus\Query\QueryHandler;
use App\Event\Repository\EventNomenclatureRepository;

final readonly class GetNomenclaturesQueryHandler implements QueryHandler
{
    public function __construct(
        private EventNomenclatureRepository $eventNomenclatureRepository
    ) {
    }

    public function __invoke(GetNomenclaturesQuery $query): EventNomenclaturesResponse
    {
        $nomenclatures = $this->eventNomenclatureRepository->findAll();
        $nomenclaturesData = [];
        if (count($nomenclatures)) {
            foreach ($nomenclatures as $nomenclature) {
                //                if (!$nomenclature->isIsAutomatic()) {
                $nomenclaturesData[] = $nomenclature->toArray();
                //                }
            }
        }

        return new EventNomenclaturesResponse($nomenclaturesData);
    }
}
