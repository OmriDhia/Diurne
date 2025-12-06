<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetMeasurements;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\MesurementRepository;

final readonly class GetMeasurementsQueryHandler implements QueryHandler
{
    public function __construct(private MesurementRepository $measurementRepository)
    {
    }

    public function __invoke(GetMeasurementsQuery $query): GetMeasurementsResponse
    {
        $measurements = $this->measurementRepository->findAll();

        $formattedMeasurements = array_map(fn($measurement) => [
            'id' => $measurement->getId(),
            'name' => $measurement->getName(),
        ], $measurements);

        return new GetMeasurementsResponse($formattedMeasurements);
    }
}
