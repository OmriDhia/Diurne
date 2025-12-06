<?php

namespace App\Contremarque\Bus\Query\GetSampleById;

use App\Contremarque\Entity\Sample;
use App\Contremarque\Repository\SampleRepository;
use App\Contremarque\Bus\Command\Sample\SampleResponse;
use App\Common\Bus\Query\QueryHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetSampleByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly SampleRepository $sampleRepository
    ) {}

    public function __invoke(GetSampleByIdQuery $query): SampleResponse
    {
        $sample = $this->sampleRepository->find($query->getId());
        if (!$sample) {
            throw new NotFoundHttpException("Sample with ID {$query->getId()} not found");
        }

        return new SampleResponse($sample);
    }
}
