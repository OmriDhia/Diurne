<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopImageByWorkshopOrderId;

use App\Common\Bus\Query\QueryHandler;
use App\Workshop\Bus\Query\GetWorkshopImage\GetWorkshopImageResponse;
use App\Workshop\Repository\WorkshopImageRepository;
use App\Workshop\Repository\WorkshopOrderRepository;

final class GetWorkshopImagesByWorkshopOrderIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly WorkshopImageRepository $workshopImageRepository,
        private readonly WorkshopOrderRepository $workshopOrderRepository,
    ) {
    }

    public function __invoke(GetWorkshopImagesByWorkshopOrderIdQuery $query): GetWorkshopImageResponse
    {
        $workshopOrder = $this->workshopOrderRepository->find($query->workshopOrderId);
        if (!$workshopOrder) {
            return new GetWorkshopImageResponse([]);
        }

        $images = $this->workshopImageRepository->findBy(['workshopOrder' => $workshopOrder]);

        return new GetWorkshopImageResponse($images);
    }
}
