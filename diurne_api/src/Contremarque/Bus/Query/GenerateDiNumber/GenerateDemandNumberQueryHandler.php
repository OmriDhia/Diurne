<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GenerateDiNumber;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\ProjectDiRepository;

final readonly class GenerateDemandNumberQueryHandler implements QueryHandler
{
    public function __construct(private ProjectDiRepository $projectDiRepository)
    {
    }

    public function __invoke(GenerateDemandNumberQuery $query): GenerateDemandNumberResponse
    {
        $demandNumber = $this->projectDiRepository->generateProjectNumber();

        return new GenerateDemandNumberResponse($demandNumber);
    }
}
