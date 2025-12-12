<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\Workshop\GetWorkshopList;

use App\Common\Bus\Query\QueryHandler;
use App\MobileAppApi\Repository\WorkshopRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetWorkshopListHandler implements QueryHandler
{
    public function __construct(
        private readonly WorkshopRepository $repository
    ) {
    }

    public function __invoke(GetWorkshopListQuery $query): array
    {
        return $this->repository->findAll();
    }
}
