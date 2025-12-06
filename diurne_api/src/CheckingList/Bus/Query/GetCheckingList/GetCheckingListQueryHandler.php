<?php

namespace App\CheckingList\Bus\Query\GetCheckingList;

use App\CheckingList\Repository\CheckingListRepository;
use App\Workshop\Repository\WorkshopOrderRepository;
use App\Common\Bus\Query\QueryHandler;

class GetCheckingListQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly CheckingListRepository $repository,
        private readonly WorkshopOrderRepository $workshopOrderRepository,
    ) {
    }

    public function __invoke(GetCheckingListQuery $query): GetCheckingListResponse
    {
        if (null !== $query->workshopOrderId) {
            $workshopOrder = $this->workshopOrderRepository->find($query->workshopOrderId);
            $lists = $workshopOrder
                ? $this->repository->findBy(['workshopOrder' => $workshopOrder])
                : [];
        } else {
            $lists = $this->repository->findAll();
        }

        return new GetCheckingListResponse($lists);
    }
}
