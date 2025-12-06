<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\SpecialTreatment;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\SpecialTreatmentRepository;

class GetAllSpecialTreatmentQueryHandler implements QueryHandler
{
    public function __construct(private readonly SpecialTreatmentRepository $specialTreatmentRepository)
    {
    }

    public function __invoke(GetAllSpecialTreatmentQuery $query): SpecialTreatmentQueryResponse
    {
        $all_specialTreatment = $this->specialTreatmentRepository->findAll();

        return new SpecialTreatmentQueryResponse($all_specialTreatment);
    }
}
