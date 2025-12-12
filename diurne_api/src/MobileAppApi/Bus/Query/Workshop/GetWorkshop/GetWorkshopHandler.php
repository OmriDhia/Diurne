<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\Workshop\GetWorkshop;

use App\Common\Bus\Query\QueryHandler;
use App\MobileAppApi\Entity\Workshop;
use App\MobileAppApi\Repository\WorkshopRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsMessageHandler]
final class GetWorkshopHandler implements QueryHandler
{
    public function __construct(
        private readonly WorkshopRepository $repository
    ) {
    }

    public function __invoke(GetWorkshopQuery $query): Workshop
    {
        $workshop = $this->repository->find($query->id);

        if (!$workshop) {
            throw new NotFoundHttpException('Workshop not found');
        }

        return $workshop;
    }
}
