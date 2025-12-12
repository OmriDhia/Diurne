<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\RN\GetRNList;

use App\Common\Bus\Query\QueryHandler;
use App\MobileAppApi\Repository\RNRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetRNListHandler implements QueryHandler
{
    public function __construct(
        private readonly RNRepository $repository
    ) {
    }

    public function __invoke(GetRNListQuery $query): array
    {
        return $this->repository->findAll();
    }
}
