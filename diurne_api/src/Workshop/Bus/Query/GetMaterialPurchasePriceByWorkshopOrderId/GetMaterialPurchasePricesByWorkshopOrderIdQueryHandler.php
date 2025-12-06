<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetMaterialPurchasePriceByWorkshopOrderId;

use App\Common\Bus\Query\QueryHandler;
use App\Workshop\Bus\Query\GetMaterialPurchasePrice\MaterialPurchasePriceResponse;
use App\Workshop\Repository\MaterialPurchasePriceRepository;
use App\Workshop\Repository\WorkshopOrderRepository;

final class GetMaterialPurchasePricesByWorkshopOrderIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly MaterialPurchasePriceRepository $materialPurchasePriceRepository,
        private readonly WorkshopOrderRepository $workshopOrderRepository,
    ) {
    }

    public function __invoke(GetMaterialPurchasePricesByWorkshopOrderIdQuery $query): MaterialPurchasePriceResponse
    {
        $workshopOrder = $this->workshopOrderRepository->find($query->workshopOrderId);
        if (!$workshopOrder) {
            return new MaterialPurchasePriceResponse([]);
        }

        $prices = $this->materialPurchasePriceRepository->findBy(['workshopOrder' => $workshopOrder]);

        return new MaterialPurchasePriceResponse($prices);
    }
}
