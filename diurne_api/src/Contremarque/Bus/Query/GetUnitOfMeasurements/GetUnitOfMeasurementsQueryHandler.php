<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetUnitOfMeasurements;

use App\Common\Bus\Query\CacheableQueryHandlerTrait;
use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Entity\UnitOfMeasurement;
use App\Contremarque\Repository\UnitOfMeasurementRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetUnitOfMeasurementsQueryHandler implements QueryHandler
{
    use CacheableQueryHandlerTrait;

    public function __construct(
        private readonly UnitOfMeasurementRepository $unitOfMeasurementRepository
    ) {}

    public function __invoke(GetUnitOfMeasurementsQuery $query): GetUnitOfMeasurementsResponse
    {
        $getAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $query->getItemsPerPage();

        $abbreviations = (1 === (int) $query->feetInchCombinated)
            ? ['cm', 'ft/inch']
            : ['cm', 'ft', 'inch'];

        if ($getAll) {
            $cacheKey = 'unit_of_measurements_' . ($query->feetInchCombinated === '1' ? 'combined' : 'separate');

            if ($query->isForceRefresh()) {
                $this->clearCache($cacheKey);
            }

            $unitsData = $this->getCachedResult(
                $cacheKey,
                fn() => $this->fetchAndMapUnits($abbreviations),
                3600
            );

            $totalItems = count($unitsData);
        } else {
            $unitsData = $this->unitOfMeasurementRepository->findBy(
                ['abbreviation' => $abbreviations],
                null,
                $limit,
                $offset
            );
            $totalItems = $this->unitOfMeasurementRepository->count(['abbreviation' => $abbreviations]);
        }

        return new GetUnitOfMeasurementsResponse(
            $unitsData,
            $totalItems,
            $query->getPage(),
            $getAll ? $totalItems : $query->getItemsPerPage()
        );
    }

    /**
     * Fetches UnitOfMeasurement entities and maps them to an array.
     *
     * @param string[] $abbreviations
     * @return array<int, array<string, mixed>>
     */
    private function fetchAndMapUnits(array $abbreviations): array
    {
        $units = $this->unitOfMeasurementRepository->findBy(['abbreviation' => $abbreviations]);
        return array_map(
            fn(UnitOfMeasurement $unit) => [
                'id' => $unit->getId(),
                'name' => $unit->getName(),
                'abbreviation' => $unit->getAbbreviation(),
            ],
            $units
        );
    }
}
