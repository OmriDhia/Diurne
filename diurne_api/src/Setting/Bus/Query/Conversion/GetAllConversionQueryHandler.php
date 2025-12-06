<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Conversion;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\ConversionRepository;

class GetAllConversionQueryHandler implements QueryHandler
{
    public function __construct(private readonly ConversionRepository $conversionRepository)
    {
    }

    public function __invoke(GetAllConversionQuery $query): ConversionQueryResponse
    {
        $allConversions = $this->conversionRepository->findAll();

        return new ConversionQueryResponse($allConversions);
    }
}
